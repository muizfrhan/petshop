@extends('layouts.app')

@section('title', $search ?: 'Semua Produk')
@section('meta_description', 'Belanja produk hewan kesyacangan di PETCARE HOUSE: makanan, suplemen, aksesoris, dan perawatan.')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="['Produk' => null]" />

            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
                <div>
                    <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight">
                        {{ $search ? 'Hasil Pencarian' : 'Semua Produk' }}
                    </h1>
                    <p class="font-body-sm text-body-sm text-outline mt-1">
                        {{ $products->total() }} produk ditemukan
                        @if ($search)
                            untuk &ldquo;{{ $search }}&rdquo;
                        @endif
                    </p>
                </div>

                <form method="GET" class="flex items-center gap-2">
                    @foreach (request()->except(['sort', 'per_page', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <label class="font-body-sm text-body-sm text-outline whitespace-nowrap" for="sort">Urutkan</label>
                    <select name="sort" id="sort" class="form-input !w-auto !h-11" onchange="this.form.submit()">
                        @foreach ([
                            'latest' => 'Terbaru',
                            'popular' => 'Terlaris',
                            'price_asc' => 'Harga Terendah',
                            'price_desc' => 'Harga Tertinggi',
                            'rating' => 'Rating Tertinggi',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['sort'] ?? 'latest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </section>

    <section class="w-full py-10 bg-surface">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Filter sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="surface-card p-5 lg:sticky lg:top-24">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-title-md text-title-md text-on-surface font-bold">Filter</h2>
                            @if (array_filter($filters))
                                <a href="{{ route('produk.index') }}" class="font-label-md text-label-md text-primary hover:underline">Reset</a>
                            @endif
                        </div>

                        <form method="GET" id="filter-form" class="space-y-5">
                            @if ($filters['q'] ?? null)
                                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                            @endif
                            @if ($filters['sort'] ?? null)
                                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                            @endif

                            {{-- Search --}}
                            <div>
                                <label class="form-label" for="q">Kata Kunci</label>
                                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}"
                                       placeholder="Cari produk..." class="form-input">
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="form-label" for="kategori">Kategori</label>
                                <select id="kategori" name="kategori" class="form-input" data-auto-submit>
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}" @selected(($filters['kategori'] ?? '') === $category->slug)>
                                            {{ $category->name }} ({{ $category->products_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Brand --}}
                            <div>
                                <label class="form-label" for="brand">Brand</label>
                                <select id="brand" name="brand" class="form-input" data-auto-submit>
                                    <option value="">Semua Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->slug }}" @selected(($filters['brand'] ?? '') === $brand->slug)>
                                            {{ $brand->name }} ({{ $brand->products_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Harga --}}
                            <div>
                                <span class="form-label">Rentang Harga</span>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min" value="{{ $filters['min'] ?? '' }}"
                                           placeholder="Min" min="0" class="form-input !h-11" aria-label="Harga minimum">
                                    <input type="number" name="max" value="{{ $filters['max'] ?? '' }}"
                                           placeholder="Maks" min="0" class="form-input !h-11" aria-label="Harga maksimum">
                                </div>
                            </div>

                            {{-- Rating --}}
                            <div>
                                <span class="form-label">Rating Minimum</span>
                                <div class="space-y-1.5">
                                    @foreach ([4, 3, 2, 1] as $rating)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $rating }}"
                                                   class="w-4 h-4 accent-primary"
                                                   @checked((int) ($filters['rating'] ?? 0) === $rating)>
                                            <x-rating :rating="$rating" :show-count="false" />
                                        </label>
                                    @endforeach
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="rating" value=""
                                               class="w-4 h-4 accent-primary"
                                               @checked(empty($filters['rating']))>
                                        <span class="font-body-sm text-body-sm text-outline">Semua Rating</span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                                Terapkan Filter
                            </button>
                        </form>
                    </div>
                </aside>

                {{-- Grid produk --}}
                <div class="lg:col-span-3">
                    @if (array_filter($filters))
                        <div class="flex flex-wrap items-center gap-2 mb-5">
                            @foreach (['q' => 'Kata kunci', 'kategori' => 'Kategori', 'brand' => 'Brand', 'min' => 'Min', 'max' => 'Maks', 'rating' => 'Rating'] as $key => $label)
                                @if (! empty($filters[$key]))
                                    <span class="badge bg-surface-container text-on-surface-variant">
                                        {{ $label }}: {{ $filters[$key] }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if ($products->isEmpty())
                        <x-empty-state
                            title="Produk tidak ditemukan"
                            description="Coba ubah kata kunci atau longgarkan filter pencarian Anda."
                            icon="search_off"
                            :action="route('produk.index')"
                            action-label="Lihat Semua Produk" />
                    @else
                        <div class="grid grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" :in-wishlist="in_array($product->id, $wishlistIds)" />
                            @endforeach
                        </div>

                        <x-pagination :paginator="$products" />
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection