@extends('layouts.admin')

@section('title', 'Pembayaran')

@section('admin-content')
    @php
        $statusLabels = [
            'unpaid' => 'Belum Bayar',
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-5">
        @foreach ($statusLabels as $key => $label)
            <a href="{{ route('admin.payments.index', ['status' => $key]) }}"
               @class([
                   'surface-card p-4 transition-colors',
                   'ring-2 ring-primary' => ($filters['status'] ?? '') === $key,
               ])>
                <p class="font-label-caps text-label-caps text-outline uppercase truncate">{{ $label }}</p>
                <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $counts[$key] ?? 0 }}</p>
            </a>
        @endforeach
    </div>

    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari nomor pesanan atau nama pelanggan..." class="form-input">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <select name="method" class="form-input" aria-label="Metode">
                    <option value="">Semua Metode</option>
                    @foreach ($methods as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['method'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-input" aria-label="Status">
                    <option value="">Semua Status</option>
                    @foreach ($statusLabels as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
        </div>
        @if (array_filter($filters))
            <div class="mt-3">
                <a href="{{ route('admin.payments.index') }}"
                   class="font-label-md text-label-md text-primary hover:underline">Reset filter</a>
            </div>
        @endif
    </form>

    <div class="surface-card overflow-hidden">
        @if ($payments->isEmpty())
            <x-empty-state title="Pembayaran tidak ditemukan" description="Coba ubah kata kunci atau filter status."
                           icon="payments" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Referensi</th>
                            <th>Pesanan</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>
                                    <span class="font-mono font-label-md text-label-md text-on-surface">
                                        {{ $payment->reference ?: '—' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $payment->order) }}"
                                       class="font-body-sm text-body-sm text-primary hover:underline">
                                        #{{ $payment->order?->order_number }}
                                    </a>
                                    <p class="font-label-md text-label-md text-outline">{{ $payment->order?->customer_name }}</p>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">{{ $payment->methodLabel() }}</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-on-surface">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">
                                        {{ $payment->paid_at?->format('d/m/Y H:i') ?? $payment->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $payment->status === 'paid',
                                        'bg-secondary-fixed text-on-secondary-fixed-variant' => $payment->status === 'pending',
                                        'bg-error-container text-on-error-container' => in_array($payment->status, ['failed', 'refunded']),
                                        'bg-surface-container text-outline' => $payment->status === 'unpaid',
                                    ])>{{ $statusLabels[$payment->status] ?? $payment->status }}</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$payments" />
                </div>
            @endif
        @endif
    </div>
@endsection