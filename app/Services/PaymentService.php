<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

/**
 * Abstraksi pembayaran.
 *
 * Saat ini hanya mendukung metode manual (Transfer Bank / QRIS / E-Wallet / COD).
 * Struktur payload + reference sudah disiapkan agar mudah ditambahkan
 * driver Midtrans / Xendit / Tripay tanpa mengubah controller.
 */
class PaymentService
{
    public function createForOrder(Order $order, string $method): Payment
    {
        $method = array_key_exists($method, config('petshop.payment_methods'))
            ? $method
            : 'bank_transfer';

        return $order->payments()->create([
            'method' => $method,
            'status' => Payment::STATUS_UNPAID,
            'reference' => $this->generateReference($method),
            'amount' => $order->total,
            'payload' => [
                'driver' => 'manual',
                'instructions' => $this->instructions($method, $order),
            ],
        ]);
    }

    public function generateReference(string $method): string
    {
        return strtoupper(Str::substr($method, 0, 3)).'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
    }

    public function instructions(string $method, Order $order): array
    {
        return match ($method) {
            'bank_transfer' => [
                'title' => 'Transfer Bank',
                'lines' => [
                    'BCA 1234567890 a.n. PT PetCare House',
                    ' Mandiri 9876543210 a.n. PT PetCare House',
                    'Total: Rp '.number_format($order->total, 0, ',', '.'),
                ],
            ],
            'qris' => [
                'title' => 'QRIS',
                'lines' => ['Pindai QRIS di kasir PETCARE HOUSE.', 'Nomor pesanan: '.$order->order_number],
            ],
            'ewallet' => [
                'title' => 'E-Wallet',
                'lines' => ['Dukungan GoPay, OVO, dan DANA.', 'Nomor pesanan: '.$order->order_number],
            ],
            'cod' => [
                'title' => 'COD (Bayar di Tempat)',
                'lines' => ['Siapkan uang tunai sejumlah total pesanan.'],
            ],
            default => ['title' => 'Pembayaran', 'lines' => []],
        };
    }

    public function markPaid(Order $order, ?string $reference = null): Order
    {
        $order->payment()->update([
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
            'reference' => $reference ?: $order->payment?->reference,
        ]);

        $order->update([
            'status' => Order::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return $order->refresh();
    }
}