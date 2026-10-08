@section('title', 'Produk Unggulan')
@section('meta_description', 'Produk unggulan PETCARE HOUSE: makanan, suplemen, dan perlengkapan hewan kesayangan.')

<section class="w-full py-16 bg-surface" id="katalog-produk">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Pilihan Terbaik</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1">Produk Unggulan Untuk Sahabatmu</h2>
            </div>
            <a href="{{ route('produk.index') }}"
               class="shrink-0 px-5 py-2.5 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-on-surface font-label-lg text-label-lg font-semibold hover:bg-surface-container-low transition-colors inline-flex items-center gap-2">
                Lihat Semua Produk
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        @if ($products->isEmpty())
            <x-empty-state
                title="Belum ada produk unggulan"
                description="Produk yang ditandai sebagai unggulan akan tampil di sini."
                icon="inventory_2"
                :action="route('produk.index')"
                action-label="Lihat Semua Produk" />
        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>
</section>