@props([
    'title',
    'eyebrow' => 'Informasi',
    'intro' => null,
    'icon' => 'info',
])

<section class="relative w-full pt-6 pb-12 lg:pb-16 overflow-hidden bg-gradient-to-b from-surface-container via-surface to-background">
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -left-20 w-80 h-80 rounded-full bg-secondary-container/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary-fixed/40 text-primary mb-5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                <span class="font-label-caps text-label-caps uppercase tracking-wider font-bold">{{ $eyebrow }}</span>
            </div>

            <h1 class="font-display-lg text-display-lg-mobile sm:text-display-lg text-on-surface font-extrabold tracking-tight mb-4">
                {{ $title }}
            </h1>

            @if ($intro)
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">{{ $intro }}</p>
            @endif
        </div>
    </div>
</section>