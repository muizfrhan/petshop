@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => true,
])

@php
    $palette = match ($variant) {
        'success' => ['bg-tertiary-fixed text-on-tertiary-fixed', 'check_circle'],
        'error' => ['bg-error-container text-on-error-container', 'error'],
        'warning' => ['bg-secondary-fixed text-on-secondary-fixed-variant', 'warning'],
        default => ['bg-primary-fixed text-on-primary-fixed-variant', 'info'],
    };
@endphp

<div class="flex items-start gap-3 px-4 py-3 rounded-2xl border border-surface-outline-app {{ $palette[0] }} {{ $attributes->merge(['class' => '']) }}"
     @if ($dismissible) data-dismissible-alert @endif
     role="alert">
    <span class="material-symbols-outlined text-[20px] mt-px shrink-0">{{ $palette[1] }}</span>

    <div class="flex-1 text-sm">
        @if ($title)
            <p class="font-label-lg text-label-lg font-bold">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-0.5' : '' }}">{{ $slot }}</div>
    </div>

    @if ($dismissible)
        <button type="button" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity" data-alert-close aria-label="Tutup">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    @endif
</div>