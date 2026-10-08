@extends('layouts.admin')

@section('title', 'Pesanan')

@section('admin-content')
    {{-- Filter --}}
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="lg:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari nomor pesanan, nama, atau email..." class="form-input">
            </div>

            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>
                        {{ $label }} ({{ $counts[$key] ?? 0 }})
                    </option>
                @endforeach
            </select>

            <select name="payment" class="form-input">
                <option value="">Semua Pembayaran</option>
                @foreach (['unpaid' => 'Belum Bayar', 'pending' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'failed' => 'Gagal'] as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['payment'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (array_filter($filters))
                <a href="{{ route('admin.orders.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $orders->total() }} pesanan</span>
        </div>
    </form>

    {{-- Ringkasan status --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
        @foreach ($statuses as $key => $label)
            <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
               @class([
                   'surface-card p-4 transition-colors',
                   'ring-2 ring-primary' => ($filters['status'] ?? '') === $key,
               ])>
                <p class="font-label-caps text-label-caps text-outline uppercase truncate">{{ $label }}</p>
                <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $counts[$key] ?? 0 }}</p>
            </a>
        @endforeach
    </div>

    <div class="surface-card overflow-hidden">
        @if ($orders->isEmpty())
            <x-empty-state
                title="Pesanan tidak ditemukan"
                description="Coba ubah filter pencarian Anda."
                icon="receipt_long"
                :action="route('admin.orders.index')"
                action-label="Reset Filter" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-on-surface">{{ $order->order_number }}</span>
                                    <p class="font-label-caps text-label-caps text-outline">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                                <td>
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $order->customer_name }}</span>
                                    <p class="font-label-md text-label-md text-outline">{{ $order->email }}</p>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">{{ $order->items_count }} produk</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-primary">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $paymentStatus = $order->payment?->status ?? 'unpaid';
                                        $map = [
                                            'unpaid' => ['bg-surface-container text-outline', 'Belum Bayar'],
                                            'pending' => ['bg-secondary-fixed text-on-secondary-fixed-variant', 'Menunggu'],
                                            'paid' => ['bg-tertiary-fixed text-tertiary-container', 'Lunas'],
                                            'failed' => ['bg-error-container text-on-error-container', 'Gagal'],
                                        ];
                                        [$classes, $label] = $map[$paymentStatus] ?? $map['unpaid'];
                                    @endphp
                                    <span class="badge {{ $classes }}">{{ $label }}</span>
                                </td>
                                <td><x-status-badge :status="$order->status" /></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
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

            @if ($orders->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$orders" />
                </div>
            @endif
        @endif
    </div>
@endsection