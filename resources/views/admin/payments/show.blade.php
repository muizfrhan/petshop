@extends('layouts.admin')

@section('title', 'Detail Pembayaran #' . str_pad($payment->id, 6, '0', STR_PAD_LEFT))
@section('subtitle', $payment->order?->order_number)

@section('admin-content')
    @php
        $statusLabels = [
            'unpaid' => 'Belum Bayar',
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];
        $statusClasses = [
            'unpaid' => 'bg-surface-container text-outline',
            'pending' => 'bg-secondary-fixed text-on-secondary-fixed-variant',
            'paid' => 'bg-tertiary-fixed text-tertiary-container',
            'failed' => 'bg-error-container text-on-error-container',
            'refunded' => 'bg-error-container text-on-error-container',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 pb-6 mb-6 border-b border-surface-container-low">
                    <div>
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-2">Status Pembayaran</p>
                        <span class="badge text-[13px] {{ $statusClasses[$payment->status] ?? 'bg-surface-container text-outline' }}">
                            {{ $statusLabels[$payment->status] ?? $payment->status }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Jumlah</p>
                        <p class="font-headline text-headline text-primary font-bold">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Referensi</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface font-semibold font-mono">
                            {{ $payment->reference ?: '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Metode</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $payment->methodLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Dibuat</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">{{ $payment->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Dibayar Pada</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">
                            {{ $payment->paid_at?->format('d/m/Y H:i') ?? 'Belum dibayar' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Rincian Pesanan</h2>

                @if ($payment->order)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-surface-container-low">
                                    <th class="text-left font-label-caps text-label-caps text-outline uppercase pb-3">Produk</th>
                                    <th class="text-center font-label-caps text-label-caps text-outline uppercase pb-3">Qty</th>
                                    <th class="text-right font-label-caps text-label-caps text-outline uppercase pb-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container-low">
                                @foreach ($payment->order->items as $item)
                                    <tr>
                                        <td class="py-3">
                                            <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $item->product_name ?? $item->name }}</p>
                                            <p class="font-label-md text-label-md text-outline">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="py-3 text-center font-label-md text-label-md text-on-surface">{{ $item->quantity }}</td>
                                        <td class="py-3 text-right font-label-md text-label-md font-semibold text-on-surface">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="pt-4 font-label-lg text-label-lg text-outline text-right">Total</td>
                                    <td class="pt-4 font-title-md text-title-md font-bold text-on-surface text-right">
                                        Rp {{ number_format($payment->order->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="font-body-sm text-body-sm text-outline">Pesanan terkait telah dihapus.</p>
                @endif
            </div>

            @if ($payment->payload)
                <div class="surface-card p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Data Transaksi</h2>
                    <pre class="bg-surface-container-low rounded-xl p-4 font-mono text-[12px] leading-relaxed text-on-surface-variant overflow-x-auto">{{ json_encode($payment->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Pesanan</h2>

                @if ($payment->order)
                    <dl class="space-y-3">
                        <div>
                            <dt class="font-label-caps text-label-caps text-outline uppercase">Nomor</dt>
                            <dd class="font-body-sm text-body-sm text-on-surface font-semibold">#{{ $payment->order->order_number }}</dd>
                        </div>
                        <div>
                            <dt class="font-label-caps text-label-caps text-outline uppercase">Status Pesanan</dt>
                            <dd class="mt-1"><x-status-badge :status="$payment->order->status" /></dd>
                        </div>
                        <div>
                            <dt class="font-label-caps text-label-caps text-outline uppercase">Pelanggan</dt>
                            <dd class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $payment->order->customer_name }}</dd>
                            <dd class="font-body-sm text-body-sm text-outline">{{ $payment->order->phone }}</dd>
                        </div>
                    </dl>

                    <a href="{{ route('admin.orders.show', $payment->order) }}"
                       class="mt-5 w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                        Lihat Pesanan
                    </a>
                @else
                    <p class="font-body-sm text-body-sm text-outline">Pesanan terkait telah dihapus.</p>
                @endif
            </div>

            <a href="{{ route('admin.payments.index') }}"
               class="w-full py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                Kembali ke Daftar Pembayaran
            </a>
        </aside>
    </div>
@endsection