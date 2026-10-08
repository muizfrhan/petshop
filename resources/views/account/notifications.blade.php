@extends('layouts.account')

@section('title', 'Notifikasi')

@section('account-content')
<x-breadcrumb :items="['Akun Saya' => route('akun.index'), 'Notifikasi' => null]" />

<h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mb-8">
    Notifikasi
</h1>

@if ($notifications->isEmpty())
    <x-empty-state
        title="Belum ada notifikasi"
        description="Pemberitahuan tentang pesanan dan booking Anda akan muncul di sini."
        icon="notifications"
        :action="route('akun.index')"
        action-label="Kembali ke Dashboard" />
@else
    <div class="surface-card overflow-hidden">
        <ul class="divide-y divide-surface-container-low">
            @foreach ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $icons = [
                        'order_created' => 'receipt_long',
                        'payment_confirmed' => 'paid',
                        'order_shipped' => 'local_shipping',
                        'booking_confirmed' => 'event_available',
                        'booking_pending' => 'event_note',
                        'booking_rescheduled' => 'event_repeat',
                        'booking_completed' => 'task_alt',
                        'booking_cancelled' => 'event_busy',
                    ];
                    $type = $data['type'] ?? 'default';
                    $icon = $icons[$type] ?? 'notifications';
                @endphp

                <li @class([
                    'flex items-start gap-4 p-5 transition-colors',
                    'bg-primary-fixed/10' => is_null($notification->read_at),
                ])>
                    <div @class([
                        'w-11 h-11 rounded-xl flex items-center justify-center shrink-0',
                        'bg-primary-fixed text-primary' => is_null($notification->read_at),
                        'bg-surface-container text-outline' => ! is_null($notification->read_at),
                    ])>
                        <span class="material-symbols-outlined text-[22px]">{{ $icon }}</span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                            {{ $data['title'] ?? 'Notifikasi' }}
                        </p>
                        <p class="font-body-sm text-body-sm text-outline leading-relaxed mt-0.5">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <p class="font-label-md text-label-md text-outline mt-1.5">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if (! empty($data['url']))
                        <a href="{{ $data['url'] }}"
                           class="px-4 py-2 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors shrink-0 self-center">
                            Lihat
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <x-pagination :paginator="$notifications" />
@endif
@endsection
