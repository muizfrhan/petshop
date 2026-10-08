@extends('layouts.account')

@section('title', 'Pesanan Saya')

@section('account-content')
<x-breadcrumb :items="['Pesanan' => null]" />

<h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mb-6">
    Riwayat Pesanan
</h1>

{{-- Filter status --}}
<div class="flex flex-wrap gap-2 mb-8">
    <a href="{{ route('pesanan.index') }}"
       @class([
           'px-4 py-2 rounded-xl font-label-md text-label-md font-semibold transition-colors',
           'bg-primary text-on-primary' => ! $currentStatus,
           'bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high' => $currentStatus,
       ])>Semua</a>

    @foreach ($statuses as $key => $label)
        <a href="{{ route('pesanan.index', ['status' => $key]) }}"
           @class([
               'px-4 py-2 rounded-xl font-label-md text-label-md font-semibold transition-colors',
               'bg-primary text-on-primary' => $currentStatus === $key,
               'bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high' => $currentStatus !== $key,
           ])>{{ $label }}</a>
    @endforeach
</div>

@if ($orders->isEmpty())
    <x-empty-state
        title="Belum ada pesanan"
        description="Pesanan yang pernah Anda buat akan tampil di sini beserta status pengirimannya."
        icon="receipt_long"
        :action="route('produk.index')"
        action-label="Mulai Belanja" />
@else
    <div class="space-y-4">
        @foreach ($orders as $order)
            <div class="surface-card p-5">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4 pb-4 border-b border-surface-container-low">
                    <div>
                        <p class="font-title-md text-title-md text-on-surface font-bold">{{ $order->order_number }}</p>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">
                            {{ $order->created_at->format('d F Y, H:i') }} WIB
                            Â· {{ config('petshop.payment_methods')[$order->payment_method] ?? $order->payment_method }}
                        </p>
                    </div>
                    <div class="text-right">
                        <x-status-badge :status="$order->status" />
                        <p class="font-headline-sm text-headline-sm text-primary font-bold mt-1.5">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex -space-x-3">
                            @foreach ($order->items->take(4) as $item)
                                <img src="{{ $item->product?->imageUrl() ?? asset('images/hero-card.jpg') }}"
                                     alt="{{ $item->product_name }}"
                                     class="w-11 h-11 rounded-xl object-cover bg-surface-container-low border-2 border-surface-container-lowest"
                                     loading="lazy">
                            @endforeach
                        </div>
                        <div class="min-w-0">
                            <p class="font-body-sm text-body-sm text-on-surface font-semibold">
                                {{ $order->items->count() }} produk
                            </p>
                            <p class="font-body-sm text-body-sm text-outline line-clamp-1">
                                {{ $order->items->pluck('product_name')->take(2)->join(', ') }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('pesanan.show', $order) }}"
                       class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <x-pagination :paginator="$orders" />
@endif
@endsection
