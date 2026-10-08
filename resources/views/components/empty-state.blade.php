@props([
    'title' => 'Belum ada data',
    'description' => null,
    'icon' => 'inventory_2',
    'action' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'w-full py-16 px-6 flex flex-col items-center justify-center text-center']) }}>
    <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center mb-5">
        <span class="material-symbols-outlined text-[40px] text-outline">{{ $icon }}</span>
    </div>

    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $title }}</h3>

    @if ($description)
        <p class="font-body-sm text-body-sm text-outline mt-2 max-w-md">{{ $description }}</p>
    @endif

    @if ($action && $actionLabel)
        <a href="{{ $action }}"
           class="mt-6 px-6 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
            {{ $actionLabel }}
        </a>
    @endif
</div>