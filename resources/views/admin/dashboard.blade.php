@extends('layouts.admin')

@section('title', 'Dashboard')

@section('admin-content')
    @php
        $isAdmin = auth()->user()->isAdmin();

        $statCards = $isAdmin
            ? [
                ['label' => 'Total Pelanggan', 'value' => $stats['total_users'], 'icon' => 'group', 'color' => 'bg-primary-fixed text-primary', 'route' => 'admin.users.index'],
                ['label' => 'Total Produk', 'value' => $stats['total_products'], 'icon' => 'inventory_2', 'color' => 'bg-surface-variant text-surface-tint', 'route' => 'admin.products.index'],
                ['label' => 'Total Pesanan', 'value' => $stats['total_orders'], 'icon' => 'receipt_long', 'color' => 'bg-secondary-fixed text-secondary', 'route' => 'admin.orders.index'],
                ['label' => 'Stok Menipis', 'value' => $stats['low_stock'], 'icon' => 'inventory', 'color' => 'bg-error-container text-on-error-container', 'route' => 'admin.products.index'],
            ]
            : [
                ['label' => 'Total Pesanan', 'value' => $stats['total_orders'], 'icon' => 'receipt_long', 'color' => 'bg-secondary-fixed text-secondary', 'route' => 'admin.orders.index'],
                ['label' => 'Layanan Aktif', 'value' => $stats['active_services'], 'icon' => 'medical_services', 'color' => 'bg-primary-fixed text-primary', 'route' => 'admin.services.index'],
                ['label' => 'Booking Hari Ini', 'value' => $stats['bookings_today'], 'icon' => 'calendar_month', 'color' => 'bg-surface-variant text-surface-tint', 'route' => 'admin.bookings.index'],
                ['label' => 'Ulasan Pending', 'value' => $stats['pending_reviews'], 'icon' => 'reviews', 'color' => 'bg-error-container text-on-error-container', 'route' => 'admin.reviews.index'],
            ];
    @endphp

    {{-- Kartu statistik utama --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ($statCards as $card)
            <a href="{{ route($card['route']) }}"
               class="surface-card surface-card-hover p-5 flex flex-col gap-3 group">
                <div class="w-11 h-11 rounded-xl {{ $card['color'] }} flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">{{ $card['icon'] }}</span>
                </div>
                <div>
                    <p class="font-label-caps text-label-caps text-outline uppercase">{{ $card['label'] }}</p>
                    <p class="font-headline-md text-headline-md text-on-surface font-bold">
                        {{ number_format($card['value'], 0, ',', '.') }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Revenue --}}
        <div class="lg:col-span-2 surface-card p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pendapatan</h2>
                    <p class="font-body-sm text-body-sm text-outline">
                        <span data-chart-readout>14 hari terakhir</span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-label-caps text-label-caps text-outline uppercase">Hari Ini</p>
                    <p class="font-headline-sm text-headline-sm text-primary font-bold">
                        Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            @php
                $maxRevenue = max(1, max(array_map(fn ($d) => $d['revenue'], $salesChart)));
                $rangeRevenue = array_sum(array_map(fn ($d) => $d['revenue'], $salesChart));
            @endphp

            <div class="relative mb-3">
                {{-- Garis bantu (gridline) --}}
                <div class="absolute inset-x-0 top-0 bottom-0 flex flex-col justify-between pointer-events-none" aria-hidden="true">
                    <div class="h-px bg-surface-container-low"></div>
                    <div class="h-px bg-surface-container-low"></div>
                    <div class="h-px bg-surface-container-low"></div>
                    <div class="h-px bg-surface-container-low"></div>
                </div>

                @if ($rangeRevenue === 0)
                    <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none px-4">
                        <span class="px-3.5 py-2 rounded-xl bg-surface-container-lowest border border-surface-outline-app font-label-md text-label-md text-outline text-center">
                            Belum ada penjualan dalam 14 hari terakhir
                        </span>
                    </div>
                @endif

                <div class="relative flex items-end gap-1.5 h-40" role="img"
                     aria-label="Grafik pendapatan 14 hari terakhir">
                    @foreach ($salesChart as $day)
                        @php
                            $height = round(($day['revenue'] / $maxRevenue) * 100, 1);
                            $hasRevenue = $day['revenue'] > 0;
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end group/bar relative min-w-0 h-full"
                             data-chart-col
                             data-chart-tip="{{ $day['label'] }} · Rp {{ number_format($day['revenue'], 0, ',', '.') }} · {{ $day['orders'] }} pesanan">
                            <div @class([
                                'w-full rounded-t-md transition-all duration-200',
                                'bg-primary group-hover/bar:bg-primary-container' => $hasRevenue,
                                'bg-surface-container-high/70 group-hover/bar:bg-surface-container-high' => ! $hasRevenue,
                            ]) @if ($hasRevenue) data-chart-bar @endif style="height: {{ max($height, 4) }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between font-label-caps text-label-caps text-outline">
                <span>{{ $salesChart[0]['label'] ?? '-' }}</span>
                <span>{{ $salesChart[count($salesChart) - 1]['label'] ?? '-' }}</span>
            </div>

            <div class="mt-5 pt-5 border-t border-surface-container-low flex flex-wrap gap-6">
                <div>
                    <p class="font-label-caps text-label-caps text-outline uppercase">Total Revenue</p>
                    <p class="font-headline-sm text-headline-sm text-on-surface font-bold">
                        Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="font-label-caps text-label-caps text-outline uppercase">Order Pending</p>
                    <p class="font-headline-sm text-headline-sm text-secondary font-bold">
                        {{ number_format($stats['order_pending'], 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="font-label-caps text-label-caps text-outline uppercase">Order Diproses</p>
                    <p class="font-headline-sm text-headline-sm text-primary font-bold">
                        {{ number_format($stats['order_processing'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Booking hari ini --}}
        <div class="surface-card p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Booking Hari Ini</h2>
                <span class="badge bg-primary-fixed text-on-primary-fixed-variant">{{ $stats['bookings_today'] }}</span>
            </div>

            @if ($todayBookings->isEmpty())
                <p class="font-body-sm text-body-sm text-outline text-center py-8">
                    Belum ada booking untuk hari ini.
                </p>
            @else
                <ul class="space-y-3">
                    @foreach ($todayBookings as $booking)
                        <li class="flex items-start gap-3 p-3 rounded-xl bg-surface-container-low">
                            <div class="w-10 h-10 rounded-xl bg-surface-container-lowest flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[20px]">pets</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-label-lg text-label-lg font-bold text-on-surface truncate">
                                    {{ $booking->pet_name }}
                                </p>
                                <p class="font-label-md text-label-md text-outline truncate">
                                    {{ $booking->service?->name ?? 'Layanan dihapus' }} · {{ $booking->booking_time }}
                                </p>
                            </div>
                            <x-booking-status-badge :status="$booking->status" />
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('admin.bookings.index') }}"
               class="mt-5 w-full py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors block text-center">
                Kelola Booking
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        {{-- Produk terlaris --}}
        <div class="surface-card p-6">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Produk Terlaris</h2>

            @if ($topProducts->isEmpty())
                <p class="font-body-sm text-body-sm text-outline text-center py-8">Belum ada penjualan.</p>
            @else
                <div class="space-y-3">
                    @foreach ($topProducts as $i => $item)
                        @php
                            $maxQty = max(1, (int) $topProducts->max('qty'));
                            $width = round(((int) $item->qty / $maxQty) * 100, 1);
                        @endphp
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-1.5">
                                <span class="font-body-sm text-body-sm text-on-surface font-semibold truncate">
                                    {{ $item->product_name }}
                                </span>
                                <span class="font-label-md text-label-md text-outline shrink-0">
                                    {{ number_format($item->qty, 0, ',', '.') }} unit
                                </span>
                            </div>
                            <div class="h-2 rounded-full bg-surface-container-low overflow-hidden">
                                <div class="h-full rounded-full bg-primary" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Stok menipis --}}
        <div class="surface-card p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Stok Menipis</h2>
                <span class="badge bg-error-container text-on-error-container">≤ {{ $lowStockProducts->count() }}</span>
            </div>

            @if ($lowStockProducts->isEmpty())
                <p class="font-body-sm text-body-sm text-outline text-center py-8">Semua stok aman.</p>
            @else
                <ul class="space-y-3">
                    @foreach ($lowStockProducts as $product)
                        <li class="flex items-center gap-3">
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                                 class="w-10 h-10 rounded-lg object-cover bg-surface-container-low shrink-0" loading="lazy">
                            <div class="min-w-0 flex-1">
                                <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">{{ $product->name }}</p>
                                <p class="font-label-md text-label-md text-outline">SKU {{ $product->sku }}</p>
                            </div>
                            <span @class([
                                'badge shrink-0',
                                'bg-error-container text-on-error-container' => $product->isOutOfStock(),
                                'bg-secondary-fixed text-on-secondary-fixed-variant' => ! $product->isOutOfStock(),
                            ])>
                                {{ $product->isOutOfStock() ? 'Habis' : $product->stock.' unit' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('admin.products.index', ['stok' => 'menipis']) }}"
               class="mt-5 w-full py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors block text-center">
                Kelola Stok
            </a>
        </div>
    </div>

    {{-- Pesanan terbaru --}}
    <div class="surface-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="font-label-md text-label-md text-primary hover:underline">
                Lihat semua
            </a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="font-body-sm text-body-sm text-outline text-center py-8">Belum ada pesanan masuk.</p>
        @else
            <div class="table-shell -mx-6 px-6">
                <table>
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-on-surface">{{ $order->order_number }}</span>
                                    <p class="font-label-caps text-label-caps text-outline">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                                <td>
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $order->customer_name }}</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-primary">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td><x-status-badge :status="$order->status" /></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="font-label-md text-label-md font-semibold text-primary hover:underline">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection