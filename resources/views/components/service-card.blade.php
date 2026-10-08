@props(['service', 'index' => 0])

@php
    $palette = match ($index % 4) {
        0 => ['bg-primary-fixed text-primary', 'text-surface-tint'],
        1 => ['bg-secondary-fixed text-secondary', 'text-on-secondary-fixed-variant'],
        2 => ['bg-tertiary-fixed text-tertiary-container', 'text-surface-tint'],
        default => ['bg-surface-variant text-surface-tint', 'text-surface-tint'],
    };
@endphp

<div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group h-full">
    <div>
        <div class="w-14 h-14 rounded-2xl {{ $palette[0] }} flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
            <span class="material-symbols-outlined text-[28px]">{{ $service->icon ?: 'spa' }}</span>
        </div>

        <span class="font-label-caps text-label-caps {{ $palette[1] }} font-bold uppercase tracking-wider">{{ Str::headline(str_replace('_', ' ', $service->category ?? 'Perawatan')) }}</span>
        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold mt-1 mb-2">
            <a href="{{ route('layanan.show', $service->slug) }}" class="hover:text-primary transition-colors">{{ $service->name }}</a>
        </h3>

        <p class="font-body-sm text-body-sm text-outline mb-4 leading-relaxed line-clamp-3">
            {{ \Illuminate\Support\Str::limit($service->description, 140) }}
        </p>

        <div class="flex items-center gap-4 text-outline font-label-md text-label-md mb-6 flex-wrap">
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">schedule</span>
                {{ $service->durationLabel() }}
            </span>
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                Dikerjakan Pet Handler
            </span>
        </div>
    </div>

    <div class="pt-4 flex items-center justify-between gap-3">
        <div>
            <p class="font-label-caps text-label-caps text-outline uppercase">Mulai Dari</p>
            <p class="font-title-md text-title-md text-primary font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('booking.create', ['layanan' => $service->slug]) }}"
           class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all shadow-sm whitespace-nowrap">
            Pesan Layanan
        </a>
    </div>
</div>