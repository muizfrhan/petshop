<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(private readonly OrderService $orders) {}

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:pending,paid,processing,shipped,completed,cancelled'],
            'q' => ['nullable', 'string', 'max:100'],
            'payment' => ['nullable', 'string', 'in:unpaid,pending,paid,failed'],
        ], [], ['payment' => 'status pembayaran']);

        $orders = Order::query()
            ->with('user', 'payment')
            ->withCount('items')
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['payment'] ?? null, fn ($q, $status) => $q->whereHas(
                'payment',
                fn ($p) => $p->where('status', $status),
            ))
            ->when($validated['q'] ?? null, fn ($q, $keyword) => $q->where(function ($sub) use ($keyword) {
                $sub->where('order_number', 'ilike', '%'.$keyword.'%')
                    ->orWhere('customer_name', 'ilike', '%'.$keyword.'%')
                    ->orWhere('email', 'ilike', '%'.$keyword.'%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => config('petshop.order_statuses'),
            'counts' => Order::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'filters' => $validated,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items.product.primaryImage', 'payment', 'user']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => config('petshop.order_statuses'),
            'allowedTransitions' => $this->allowedTransitions($order->status),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:paid,processing,shipped,completed,cancelled'],
            'payment_reference' => ['nullable', 'string', 'max:80'],
        ], [], ['status' => 'status']);

        try {
            if ($validated['status'] === Order::STATUS_PAID) {
                $this->orders->markPaid($order, $validated['payment_reference'] ?? null);

                return back()->with('success', 'Pembayaran pesanan '.$order->order_number.' dikonfirmasi.');
            }

            if ($validated['status'] === Order::STATUS_CANCELLED) {
                $this->orders->cancel($order);

                return back()->with('success', 'Pesanan '.$order->order_number.' dibatalkan.');
            }

            $this->orders->changeStatus($order, $validated['status']);

            if ($validated['status'] === Order::STATUS_SHIPPED && $order->user) {
                $order->user->notify(new \App\Notifications\OrderShippedNotification($order));
            }
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status pesanan diperbarui menjadi "'.$order->fresh()->statusLabel().'".');
    }

    private function allowedTransitions(string $current): array
    {
        return match ($current) {
            Order::STATUS_PENDING => [Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PAID => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED],
            Order::STATUS_SHIPPED => [Order::STATUS_COMPLETED],
            default => [],
        };
    }
}