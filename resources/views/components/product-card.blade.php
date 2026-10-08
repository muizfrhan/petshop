@props(['product', 'inWishlist' => false])

@php
    $image = $product->primaryImage->first();
    $outOfStock = $product->isOutOfStock();
    $discount = $product->discountPercent();
    $rating = (float) $product->rating_average;
@endphp

<article class="product-card group flex flex-col rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative">
    <div class="relative w-full aspect-square rounded-xl bg-surface-container-low overflow-hidden mb-4">
        @if ($outOfStock)
            <span class="absolute top-2.5 left-2.5 z-10 px-2.5 py-1 rounded-full bg-inverse-surface text-inverse-on-surface font-label-caps text-label-caps font-bold">Stok Habis</span>
        @elseif ($discount > 0)
            <span class="absolute top-2.5 left-2.5 z-10 px-2.5 py-1 rounded-full bg-secondary text-on-secondary font-label-caps text-label-caps font-bold">DISKON {{ $discount }}%</span>
        @endif

        @if ($product->featured)
            <span class="absolute top-2.5 left-2.5 z-10 px-2.5 py-1 rounded-full bg-primary-container text-on-primary-container font-label-caps text-label-caps font-bold {{ $discount > 0 && ! $outOfStock ? 'top-12' : '' }}">UNGGULAN</span>
        @endif

        @auth
            <button type="button"
                    class="wishlist-btn absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-surface-container-lowest/90 backdrop-blur-sm flex items-center justify-center shadow-sm transition-colors {{ $inWishlist ? 'text-secondary' : 'text-outline hover:text-secondary' }}"
                    data-wishlist-btn
                    data-product-id="{{ $product->id }}"
                    title="{{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}"
                    aria-label="{{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}">
                <span class="material-symbols-outlined text-[20px]" @if ($inWishlist) style="font-variation-settings: 'FILL' 1;" @endif>favorite</span>
            </button>
        @endauth

        <a href="{{ route('produk.show', $product->slug) }}" class="block w-full h-full">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 src="{{ $product->imageUrl() }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 width="512" height="512">
        </a>
    </div>

    <div class="flex-1 flex flex-col justify-between">
        <div>
            @if ($rating > 0)
                <div class="flex items-center gap-1 text-surface-tint mb-1.5">
                    <span class="material-symbols-outlined text-[16px] text-amber-500" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="font-label-md text-label-md text-on-surface font-bold">{{ number_format($rating, 1, ',', '.') }}</span>
                    <span class="font-label-caps text-label-caps text-outline">({{ $product->rating_count }} ulasan)</span>
                </div>
            @endif

            @if ($product->category)
                <a href="{{ route('kategori.show', $product->category->slug) }}"
                   class="font-label-caps text-label-caps text-primary uppercase font-bold tracking-wider mb-1 inline-block hover:underline">{{ $product->category->name }}</a>
            @endif

            <h3 class="font-title-md text-title-md text-on-surface font-bold line-clamp-1">
                <a href="{{ route('produk.show', $product->slug) }}" class="hover:text-primary transition-colors">{{ $product->name }}</a>
            </h3>

            @if ($product->brand)
                <p class="font-body-sm text-body-sm text-outline line-clamp-1 mt-0.5">{{ $product->brand->name }}</p>
            @endif
        </div>

        <div class="pt-4 mt-3">
            <div class="flex items-baseline gap-2 mb-3 flex-wrap">
                <span class="font-headline-sm text-headline-sm text-primary font-bold">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                @if ($product->isOnSale())
                    <span class="font-body-sm text-body-sm text-outline line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>

            @if ($outOfStock)
                <button type="button"
                        class="w-full py-2.5 px-4 rounded-xl bg-surface-variant text-on-surface-variant font-label-lg text-label-lg font-bold cursor-not-allowed"
                        disabled>Stok Habis</button>
            @else
                <button type="button"
                        class="w-full py-2.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg font-bold hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 shadow-sm"
                        data-add-to-cart
                        data-product-id="{{ $product->id }}">
                    <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                    <span>Tambah Keranjang</span>
                </button>
            @endif
        </div>
    </div>
</article>