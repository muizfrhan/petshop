@props([
    'rating' => 0,
    'count' => null,
    'size' => 'sm',
    'showCount' => true,
])

@php
    $full = (int) floor($rating);
    $half = ($rating - $full) >= 0.5;
    $stars = max(1, min(5, (int) round($rating)));
@endphp

<span class="inline-flex items-center gap-0.5" title="Rating {{ number_format($rating, 1, ',', '.') }} dari 5">
    @for ($i = 1; $i <= 5; $i++)
        @php
            $filled = $i <= $full || ($i === $full + 1 && $half);
            $iconSize = $size === 'lg' ? 'text-[20px]' : 'text-[16px]';
        @endphp
        <span class="material-symbols-outlined {{ $iconSize }} {{ $filled ? 'text-amber-500' : 'text-outline' }}"
              @if ($filled) style="font-variation-settings: 'FILL' 1;" @endif>star</span>
    @endfor

    <span class="font-label-md text-label-md text-on-surface font-bold ml-1">{{ number_format($rating, 1, ',', '.') }}</span>

    @if ($showCount && $count !== null)
        <span class="font-label-caps text-label-caps text-outline">({{ $count }} ulasan)</span>
    @endif
</span>