@extends('layouts.app')

@section('title', $product->name)

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="[
                'Produk' => route('produk.index'),
                ($product->category?->name ?? 'Umum') => $product->category ? route('kategori.show', $product->category->slug) : null,
                $product->name => null,
            ]" />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16">
                {{-- Galeri --}}
                <div>
                    <div class="relative rounded-3xl overflow-hidden bg-surface-container-low aspect-square mb-4">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                             id="main-product-image" class="w-full h-full object-cover"
                             width="512" height="512">

                        @if ($product->isOutOfStock())
                            <div class="absolute inset-0 bg-inverse-surface/50 flex items-center justify-center">
                                <span class="px-6 py-3 rounded-2xl bg-surface-container-lowest font-headline-sm text-headline-sm font-bold text-error">
                                    Stok Habis
                                </span>
                            </div>
                        @elseif ($product->isOnSale())
                            <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full bg-secondary text-on-secondary font-label-caps text-label-caps font-bold">
                                DISKON {{ $product->discountPercent() }}%
                            </span>
                        @endif

                        @auth
                            <button type="button"
                                    class="absolute top-4 right-4 w-11 h-11 rounded-full bg-surface-container-lowest/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition-colors {{ $inWishlist ? 'text-secondary' : 'text-outline hover:text-secondary' }}"
                                    data-wishlist-btn
                                    data-product-id="{{ $product->id }}"
                                    title="Tambah ke Wishlist">
                                <span class="material-symbols-outlined text-[22px]" @if ($inWishlist) style="font-variation-settings: 'FILL' 1;" @endif>favorite</span>
                            </button>
                        @endauth
                    </div>

                    @if ($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-3">
                            @foreach ($product->images as $image)
                                <button type="button"
                                        class="aspect-square rounded-xl overflow-hidden bg-surface-container-low border-2 transition-colors hover:border-primary"
                                        data-thumb="{{ $image->url() }}">
                                    <img src="{{ $image->url() }}" alt="{{ $image->alt }}" class="w-full h-full object-cover" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Informasi produk --}}
                <div>
                    @if ($product->category)
                        <a href="{{ route('kategori.show', $product->category->slug) }}"
                           class="font-label-caps text-label-caps text-primary uppercase font-bold tracking-wider hover:underline">
                            {{ $product->category->name }}
                        </a>
                    @endif

                    <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1 mb-3">
                        {{ $product->name }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 mb-5">
                        @if ($product->rating_count > 0)
                            <x-rating :rating="$product->rating_average" :count="$product->rating_count" />
                        @else
                            <span class="font-body-sm text-body-sm text-outline">Belum ada ulasan</span>
                        @endif

                        @if ($product->brand)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md font-semibold">
                                <span class="material-symbols-outlined text-[14px]">sell</span>
                                {{ $product->brand->name }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-baseline gap-3 mb-1">
                        <span class="font-display-lg text-display-lg-mobile sm:text-headline-xl text-primary font-bold">
                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                        </span>
                        @if ($product->isOnSale())
                            <span class="font-title-md text-title-md text-outline line-through">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                    <p class="font-body-sm text-body-sm text-outline mb-6">Harga sudah termasuk pajak</p>

                    <div class="grid grid-cols-2 gap-4 mb-6 py-5 border-y border-surface-container-low">
                        <div>
                            <p class="font-label-caps text-label-caps text-outline uppercase mb-1">SKU</p>
                            <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $product->sku }}</p>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Berat</p>
                            <p class="font-body-sm text-body-sm text-on-surface font-semibold">
                                {{ number_format($product->weight, 0, ',', '.') }} gram
                            </p>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Stok</p>
                            <p @class([
                                'font-body-sm text-body-sm font-semibold',
                                'text-error' => $product->isOutOfStock(),
                                'text-secondary' => ! $product->isOutOfStock() && $product->isLowStock(),
                                'text-on-surface' => ! $product->isLowStock(),
                            ])>
                                {{ $product->isOutOfStock() ? 'Stok Habis' : $product->stock.' unit tersedia' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Terjual</p>
                            <p class="font-body-sm text-body-sm text-on-surface font-semibold">
                                {{ number_format($product->sold_count, 0, ',', '.') }} unit
                            </p>
                        </div>
                    </div>

                    @if ($product->isOutOfStock())
                        <button type="button" disabled
                                class="w-full py-4 rounded-xl bg-surface-variant text-on-surface-variant font-headline-sm text-headline-sm font-bold cursor-not-allowed">
                            Stok Habis
                        </button>
                        <p class="font-body-sm text-body-sm text-outline text-center mt-3">
                            Produk ini sedang kosong. <a href="{{ route('produk.index') }}" class="text-primary hover:underline">Lihat produk lain</a>
                        </p>
                    @else
                        <form action="{{ route('keranjang.store') }}" method="POST" id="add-to-cart-form" class="space-y-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="flex gap-3">
                                <div class="flex items-center bg-surface-container-lowest border border-surface-outline-app rounded-xl">
                                    <button type="button" class="p-3 rounded-l-xl hover:bg-surface-container-low transition-colors"
                                            data-detail-qty="-1" aria-label="Kurangi">
                                        <span class="material-symbols-outlined text-[20px]">remove</span>
                                    </button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                           id="quantity-input"
                                           class="w-14 text-center bg-transparent font-title-md text-title-md font-bold text-on-surface focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none"
                                           aria-label="Jumlah">
                                    <button type="button" class="p-3 rounded-r-xl hover:bg-surface-container-low transition-colors"
                                            data-detail-qty="1" aria-label="Tambah">
                                        <span class="material-symbols-outlined text-[20px]">add</span>
                                    </button>
                                </div>

                                <button type="submit"
                                        class="flex-1 py-4 rounded-xl bg-secondary text-on-primary font-headline-sm text-headline-sm font-bold hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 shadow-md">
                                    <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                                    Tambah Keranjang
                                </button>
                            </div>
                        </form>

                        <div class="flex gap-3 mt-3">
                            <a href="{{ route('keranjang.buy-now.index', ['product' => $product->id]) }}"
                               class="flex-1 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all text-center">
                                Beli Sekarang
                            </a>
                        </div>
                    @endif

                    <div class="mt-6 space-y-3">
                        @foreach ([
                            ['local_shipping', 'Gratis ongkir untuk pembelian di atas Rp 150.000'],
                            ['verified', '100% produk original dengan garansi'],
                            ['medical_services', 'Ditangani profesional dan berlisensi'],
                        ] as [$icon, $text])
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[18px] text-primary shrink-0">{{ $icon }}</span>
                                <span class="font-body-sm text-body-sm text-on-surface">{{ $text }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Deskripsi & spesifikasi --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                <div class="lg:col-span-2 surface-card p-6 lg:p-8">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Deskripsi Produk</h2>
                    <div class="prose-pch text-on-surface-variant leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    @if ($product->specification)
                        <h3 class="font-title-md text-title-md text-on-surface font-bold mt-8 mb-3">Spesifikasi</h3>
                        <dl class="divide-y divide-surface-container-low">
                            @foreach (array_filter(array_map('trim', explode("\n", $product->specification))) as $line)
                                @php [$key, $value] = array_pad(explode(':', $line, 2), 2, ''); @endphp
                                <div class="flex justify-between gap-4 py-2.5">
                                    <dt class="font-body-sm text-body-sm text-outline">{{ trim($key) }}</dt>
                                    <dd class="font-body-sm text-body-sm text-on-surface font-semibold text-right">{{ trim($value) }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>

                <div class="surface-card p-6 h-fit">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Informasi Toko</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[20px] text-primary shrink-0">store</span>
                            <div>
                                <p class="font-label-lg text-label-lg font-semibold text-on-surface">{{ config('petshop.store.name') }}</p>
                                <p class="font-body-sm text-body-sm text-outline">Jakarta, Indonesia</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[20px] text-primary shrink-0">schedule</span>
                            <div>
                                <p class="font-label-lg text-label-lg font-semibold text-on-surface">Jam Operasional</p>
                                <p class="font-body-sm text-body-sm text-outline">Setiap hari 08.00 - 21.00 WIB</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[20px] text-primary shrink-0">support_agent</span>
                            <div>
                                <p class="font-label-lg text-label-lg font-semibold text-on-surface">Bantuan</p>
                                <a href="https://wa.me/{{ config('petshop.store.whatsapp') }}" target="_blank" rel="noopener"
                                   class="font-body-sm text-body-sm text-primary hover:underline">
                                    {{ config('petshop.store.email') }}
                                </a>
                            </div>
                        </li>
                    </ul>

                    <a href="{{ route('booking.create') }}"
                       class="mt-6 w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
Butuh layanan perawatan?
                            Booking Layanan
                    </a>
                </div>
            </div>

            {{-- Ulasan --}}
            <div class="mb-16" id="ulasan">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">
                        Ulasan Pembeli ({{ $product->rating_count }})
                    </h2>

                    @auth
                        @if (! $product->reviews->contains('user_id', auth()->id()))
                            <button type="button" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors"
                                    data-modal-open="review-modal">
                                Tulis Ulasan
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                            Masuk untuk Menulis Ulasan
                        </a>
                    @endauth
                </div>

                @if ($product->reviews->isEmpty())
                    <x-empty-state
                        title="Belum ada ulasan"
                        description="Jadilah yang pertama memberikan ulasan untuk produk ini."
                        icon="reviews" />
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($product->reviews as $review)
                            <div class="surface-card p-5">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-label-lg text-label-lg font-semibold text-on-surface truncate">
                                            {{ $review->user?->name ?? 'Pelanggan' }}
                                        </p>
                                        <p class="font-label-caps text-label-caps text-outline">
                                            {{ $review->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <x-rating :rating="$review->rating" :show-count="false" />
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                    {{ $review->comment }}
                                </p>
                                @if ($review->order)
                                    <p class="mt-3 flex items-center gap-1.5 font-label-md text-label-md text-primary">
                                        <span class="material-symbols-outlined text-[14px]">verified</span>
                                        Pembelian terverifikasi
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Produk terkait --}}
            @if ($related->isNotEmpty())
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-6">Produk Lainnya</h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach ($related as $item)
                            <x-product-card :product="$item" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</main>

{{-- Modal tulis ulasan --}}
@auth
    <x-modal name="review" title="Tulis Ulasan" icon="rate_review">
        <p class="font-body-sm text-body-sm text-outline mb-5">
            Ulasan hanya dapat diberikan untuk produk yang sudah Anda beli dan pesanannya selesai.
        </p>

        <form action="{{ route('review.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div>
                <span class="form-label">Rating <span class="text-error">*</span></span>
                <div class="flex items-center gap-1.5" id="rating-picker">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" @checked($i === 5)>
                            <span class="material-symbols-outlined text-[28px] text-outline peer-checked:text-amber-500 transition-colors"
                                  style="font-variation-settings: 'FILL' 1;">star</span>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="mt-1.5 font-body-sm text-body-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <x-form-field name="comment" label="Ulasan Anda" type="textarea"
                          placeholder="Bagaimana pengalaman Anda dengan produk ini?" />

            <button type="submit" class="w-full py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                Kirim Ulasan
            </button>
        </form>
    </x-modal>
@endauth

@push('scripts')
<script>
    // Galeri: klik thumbnail mengganti gambar utama
    document.querySelectorAll('[data-thumb]').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const main = document.getElementById('main-product-image');
            if (main) main.src = thumb.dataset.thumb;
        });
    });

    // Stepper jumlah
    document.querySelectorAll('[data-detail-qty]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById('quantity-input');
            if (!input) return;
            const next = Number(input.value) + Number(btn.dataset.detailQty);
            input.value = Math.min(Math.max(1, next), Number(input.max));
        });
    });
</script>
@endpush
@endsection