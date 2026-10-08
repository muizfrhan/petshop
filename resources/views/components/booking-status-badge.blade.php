@props(['status' => 'pending'])

@php
    $map = [
        'pending' => ['bg-secondary-fixed text-on-secondary-fixed-variant', 'Menunggu Konfirmasi'],
        'confirmed' => ['bg-primary-fixed text-on-primary-fixed-variant', 'Dikonfirmasi'],
        'rescheduled' => ['bg-tertiary-fixed text-tertiary-container', 'Dijadwalkan Ulang'],
        'completed' => ['bg-tertiary-fixed text-tertiary-container', 'Selesai'],
        'cancelled' => ['bg-error-container text-on-error-container', 'Dibatalkan'],
    ];
    [$classes, $label] = $map[$status] ?? ['bg-surface-container text-outline', $status];
@endphp

<span class="badge {{ $classes }}">{{ $label }}</span>