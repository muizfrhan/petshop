@props([
    'label',
    'route',
    'icon' => 'pets',
    'productsCount' => null,
    'color' => 'primary',
])

@php
    // Class ditulis literal agar terdeteksi scanner Tailwind (bukan interpolasi).
    $palette = match ($color) {
        'secondary' => ['bg-secondary-fixed text-secondary'],
        'tertiary' => ['bg-tertiary-fixed text-tertiary-container'],
        'variant' => ['bg-surface-variant text-surface-tint'],
        'primary-dim' => ['bg-primary-fixed-dim text-on-primary-fixed-variant'],
        'secondary-dim' => ['bg-secondary-fixed-dim text-on-secondary-fixed-variant'],
        default => ['bg-primary-fixed text-primary'],
    };
@endphp

<a class="group p-5 rounded-2xl bg-surface-container-low hover:bg-surface-container-high transition-all flex flex-col items-center text-center shadow-sm hover:shadow-md hover:-translate-y-1"
   href="{{ $route }}">
    <div class="w-16 h-16 rounded-full {{ $palette[0] }} flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined text-[30px]">{{ $icon }}</span>
    </div>
    <h3 class="font-title-md text-title-md text-on-surface font-bold group-hover:text-primary transition-colors">{{ $label }}</h3>
    <span class="font-label-md text-label-md text-outline mt-1">
        {{ $productsCount !== null ? $productsCount.' Produk' : 'Lihat Produk' }}
    </span>
</a>