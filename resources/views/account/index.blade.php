@extends('layouts.account')

@section('title', 'Dashboard Saya')

@section('account-content')
<x-breadcrumb :items="['Akun Saya' => null]" />

{{-- Header --}}
<div class="surface-card p-6 lg:p-8 mb-8">
    <div class="flex flex-wrap items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            @if ($user->avatar)
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                     class="w-16 h-16 rounded-2xl object-cover">
            @else
                <div class="w-16 h-16 rounded-2xl bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[30px]">person</span>
                </div>
            @endif
            <div>
                <h1 class="font-headline-md text-headline-md text-on-surface font-bold tracking-tight">
                    Halo, {{ Str::before($user->name, ' ') }}!
                </h1>
                <p class="font-body-sm text-body-sm text-outline">{{ $user->email }}</p>
                @if (! $user->hasVerifiedEmail())
                    <p class="mt-2 inline-flex items-center gap-1.5 font-label-md text-label-md text-secondary font-semibold">
                        <span class="material-symbols-outlined text-[16px]">warning</span>
                        Email belum diverifikasi
                        <a href="{{ route('verification.resend') }}" class="underline">Kirim ulang</a>
                    </p>
                @endif
            </div>
        </div>

        <a href="{{ route('akun.edit') }}"
           class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Edit Profil
        </a>
    </div>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach ([
        ['label' => 'Total Pesanan', 'value' => $stats['total_orders'], 'icon' => 'receipt_long', 'color' => 'bg-primary-fixed text-primary', 'route' => 'akun.pesanan'],
        ['label' => 'Pesanan Selesai', 'value' => $stats['completed_orders'], 'icon' => 'task_alt', 'color' => 'bg-tertiary-fixed text-tertiary-container', 'route' => 'akun.pesanan'],
        ['label' => 'Wishlist', 'value' => $wishlistCount, 'icon' => 'favorite', 'color' => 'bg-secondary-fixed text-secondary', 'route' => 'wishlist.index'],
        ['label' => 'Total Belanja', 'value' => 'Rp ' . number_format($stats['total_spent'], 0, ',', '.'), 'icon' => 'payments', 'color' => 'bg-surface-variant text-surface-tint', 'route' => 'akun.pesanan'],
    ] as $stat)
        <a href="{{ route($stat['route']) }}"
           class="surface-card surface-card-hover p-5 flex flex-col gap-3">
            <div class="w-11 h-11 rounded-xl {{ $stat['color'] }} flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">{{ $stat['icon'] }}</span>
            </div>
            <div>
                <p class="font-label-caps text-label-caps text-outline uppercase">{{ $stat['label'] }}</p>
                <p class="font-headline-sm text-headline-sm text-on-surface font-bold truncate">{{ $stat['value'] }}</p>
            </div>
        </a>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Pesanan terbaru --}}
    <div class="surface-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pesanan Terbaru</h2>
            <a href="{{ route('akun.pesanan') }}" class="font-label-md text-label-md text-primary hover:underline">
                Lihat semua
            </a>
        </div>

        @if ($orders->isEmpty())
            <x-empty-state
                title="Belum ada pesanan"
                description="Pesanan Anda akan tampil di sini."
                icon="receipt_long"
                :action="route('produk.index')"
                action-label="Mulai Belanja" />
        @else
            <ul class="space-y-3">
                @foreach ($orders as $order)
                    <li class="flex items-center justify-between gap-3 p-3 rounded-xl bg-surface-container-low">
                        <div class="min-w-0">
                            <a href="{{ route('pesanan.show', $order) }}"
                               class="font-label-lg text-label-lg font-bold text-on-surface hover:text-primary">
                                {{ $order->order_number }}
                            </a>
                            <p class="font-label-md text-label-md text-outline">
                                {{ $order->created_at->format('d/m/Y') }} Â· {{ $order->items_count }} produk
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <x-status-badge :status="$order->status" />
                            <p class="font-label-md text-label-md font-bold text-primary mt-1">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Booking & notifikasi --}}
    <div class="space-y-8">
        <div class="surface-card p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Booking Terbaru</h2>
            </div>

            @if ($bookings->isEmpty())
                <p class="font-body-sm text-body-sm text-outline text-center py-6">
                    Belum ada booking layanan.
                </p>
            @else
                <ul class="space-y-3">
                    @foreach ($bookings as $booking)
                        <li class="flex items-center justify-between gap-3 p-3 rounded-xl bg-surface-container-low">
                            <div class="min-w-0">
                                <a href="{{ route('booking.show', $booking) }}"
                                   class="font-label-lg text-label-lg font-bold text-on-surface hover:text-primary truncate block">
                                    {{ $booking->service?->name ?? 'Layanan' }}
                                </a>
                                <p class="font-label-md text-label-md text-outline">
                                    {{ $booking->pet_name }} Â· {{ $booking->booking_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <x-booking-status-badge :status="$booking->status" />
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('booking.create') }}"
               class="mt-4 w-full py-2.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors block text-center">
                Buat Booking Baru
            </a>
        </div>

        <div class="surface-card p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Notifikasi</h2>
                <a href="{{ route('akun.notifikasi') }}" class="font-label-md text-label-md text-primary hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($unreadNotifications->isEmpty())
                <p class="font-body-sm text-body-sm text-outline text-center py-6">
                    Tidak ada notifikasi baru.
                </p>
            @else
                <ul class="space-y-3">
                    @foreach ($unreadNotifications as $notification)
                        <li class="flex items-start gap-3 p-3 rounded-xl bg-primary-fixed/20">
                            <span class="material-symbols-outlined text-[20px] text-primary shrink-0 mt-px">
                                notifications
                            </span>
                            <div class="min-w-0">
                                <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <p class="font-body-sm text-body-sm text-outline line-clamp-2">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
