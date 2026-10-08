@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'disabled' => false,
])

@php
    $classes = match ($variant) {
        'accent' => 'bg-secondary text-on-primary hover:bg-secondary/90 shadow-md',
        'ghost' => 'bg-surface-container-lowest text-on-surface border border-surface-outline-app hover:bg-surface-container-low',
        'soft' => 'bg-surface-container-high text-on-surface hover:bg-surface-container-highest',
        'danger' => 'bg-error text-on-error hover:opacity-90',
        default => 'bg-primary text-on-primary hover:bg-primary-container',
    };

    $base = 'inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-label-lg text-label-lg font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base.' '.$classes]) }}>
        @if ($icon)<span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => $base.' '.$classes]) }}>
        @if ($icon)<span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>@endif
        {{ $slot }}
    </button>
@endif