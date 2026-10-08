<section class="w-full py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Eksplorasi Menurut Jenis</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1">Cari Kebutuhan Hewan Kesayanganmu</h2>
            </div>
            <p class="font-body-sm text-body-sm text-outline max-w-sm">
                Pilih jenis sahabat berbulu atau bersirip Anda untuk melihat nutrisi & aksesoris yang paling relevan.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
            @forelse ($categories as $category)
                <x-category-card
                    :label="$category->name"
                    :icon="$category->icon ?: 'pets'"
                    :route="route('kategori.show', $category->slug)"
                    :products-count="$category->products_count"
                    :color="match ($loop->index % 6) {
                        0 => 'primary',
                        1 => 'secondary',
                        2 => 'tertiary',
                        3 => 'variant',
                        4 => 'primary-dim',
                        default => 'secondary-dim',
                    }" />
            @empty
                @for ($i = 0; $i < 6; $i++)
                    <div class="p-5 rounded-2xl bg-surface-container-low flex flex-col items-center text-center animate-pulse">
                        <div class="w-16 h-16 rounded-full bg-surface-container mb-3"></div>
                        <div class="h-4 w-20 rounded bg-surface-container mb-2"></div>
                        <div class="h-3 w-14 rounded bg-surface-container"></div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>