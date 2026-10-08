<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderShippedNotification;
use App\Notifications\PaymentConfirmedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    /**
     * Transisi status yang diizinkan.
     * Admin tidak boleh melompat status secara bebas.
     */
    private const TRANSITIONS = [
        Order::STATUS_PENDING => [Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
        Order::STATUS_PAID => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
        Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED],
        Order::STATUS_SHIPPED => [Order::STATUS_COMPLETED],
        Order::STATUS_COMPLETED => [],
        Order::STATUS_CANCELLED => [],
    ];

    public function changeStatus(Order $order, string $status, ?string $note = null): Order
    {
        if ($status === $order->status) {
            return $order;
        }

        if (! in_array($status, self::TRANSITIONS[$order->status] ?? [], true)) {
            throw new \InvalidArgumentException(
                "Status pesanan tidak dapat diubah dari '{$order->status}' menjadi '{$status}'.",
            );
        }

        return DB::transaction(function () use ($order, $status) {
            $order->status = $status;

            if ($status === Order::STATUS_PAID && ! $order->paid_at) {
                $order->paid_at = now();
            }

            if ($status === Order::STATUS_COMPLETED) {
                $order->completed_at = now();
            }

            $order->save();

            if ($status === Order::STATUS_PAID) {
                $order->payment()->update([
                    'status' => Payment::STATUS_PAID,
                    'paid_at' => now(),
                ]);
            }

            return $order->refresh();
        });
    }

    public function markPaid(Order $order, ?string $reference = null): Order
    {
        $order->payment()->update([
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
            'reference' => $reference ?: $order->payment?->reference,
        ]);

        $this->changeStatus($order, Order::STATUS_PAID);

        $this->notify($order);

        return $order->refresh();
    }

    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            // stok dikembalikan bila pesanan sudah pernah diproses
            if (in_array($order->status, [Order::STATUS_PAID, Order::STATUS_PROCESSING], true)) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                        $item->product->decrement('sold_count', min($item->quantity, (int) $item->product->sold_count));
                    }
                }
            }

            return $this->changeStatus($order, Order::STATUS_CANCELLED);
        });
    }

    private function notify(Order $order): void
    {
        if ($order->user && $order->user->email) {
            $order->user->notify(new OrderCreatedNotification($order));

            if ($order->status === Order::STATUS_PAID) {
                $order->user->notify(new PaymentConfirmedNotification($order));
            }
        }
    }
}