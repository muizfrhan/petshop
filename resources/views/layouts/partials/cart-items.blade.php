@php
    $isEmpty = $cartItems->isEmpty();
@endphp

@if ($isEmpty)
    <div class="flex flex-col items-center justify-center text-center py-16" data-cart-empty>
        <div class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-[40px] text-outline">shopping_cart</span>
        </div>
        <p class="font-title-md text-title-md text-on-surface font-bold">Keranjangmu masih kosong</p>
        <p class="font-body-sm text-body-sm text-outline mt-1 mb-5">Yuk, temukan produk untuk sahabat berbulu kamu.</p>
        <a href="{{ route('produk.index') }}"
           class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
            Mulai Belanja
        </a>
    </div>
@else
    @foreach ($cartItems as $item)
        @php
            $product = $item->product;
            $price = $product->final_price;
            $subtotal = $price * $item->quantity;
            $exceedsStock = $product->stock < $item->quantity;
        @endphp
        <div class="flex gap-3 p-3 rounded-xl bg-surface-container-low {{ $exceedsStock ? 'ring-2 ring-error' : '' }}"
             data-cart-item="{{ $item->id }}">
            <a href="{{ route('produk.show', $product->slug) }}" class="shrink-0">
                @if ($product->primaryImage->first())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                         class="w-16 h-16 rounded-lg object-cover bg-surface-variant" loading="lazy">
                @else
                    <div class="w-16 h-16 rounded-lg bg-surface-variant flex items-center justify-center text-outline">
                        <span class="material-symbols-outlined text-[28px]">package_2</span>
                    </div>
                @endif
            </a>

            <div class="flex-1 min-w-0">
                <p class="font-title-md text-title-md text-on-surface line-clamp-1">
                    <a href="{{ route('produk.show', $product->slug) }}" class="hover:text-primary">{{ $product->name }}</a>
                </p>

                @if ($exceedsStock)
                    <p class="font-label-md text-label-md text-error mt-0.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        Stok tersisa {{ $product->stock }}
                    </p>
                @endif

                <div class="flex items-center justify-between mt-2 gap-2">
                    <div class="flex items-center gap-1 bg-surface-container-lowest rounded-lg border border-surface-outline-app">
                        <button type="button"
                                class="p-1.5 rounded-l-lg hover:bg-surface-container-low transition-colors disabled:opacity-40"
                                data-cart-qty="{{ $item->id }}"
                                data-delta="-1"
                                aria-label="Kurangi jumlah">
                            <span class="material-symbols-outlined text-[16px]">remove</span>
                        </button>

                        <input type="number"
                               class="w-12 text-center bg-transparent font-label-md text-label-md font-bold text-on-surface focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none"
                               value="{{ $item->quantity }}"
                               min="1"
                               max="{{ max($product->stock, 1) }}"
                               data-cart-qty-input="{{ $item->id }}"
                               aria-label="Jumlah produk">

                        <button type="button"
                                class="p-1.5 rounded-r-lg hover:bg-surface-container-low transition-colors disabled:opacity-40"
                                data-cart-qty="{{ $item->id }}"
                                data-delta="1"
                                aria-label="Tambah jumlah">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                        </button>
                    </div>

                    <div class="text-right">
                        <span class="font-label-lg text-label-lg text-primary font-bold">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                        <p class="font-label-caps text-label-caps text-outline">{{ $item->quantity }} x Rp {{ number_format($price, 0, ',', '.') }}</p>
                    </div>

                    <button type="button"
                            class="text-error hover:text-error/80 transition-colors shrink-0"
                            data-cart-remove="{{ $item->id }}"
                            aria-label="Hapus dari keranjang">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <form action="{{ route('keranjang.clear') }}" method="POST" class="pt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full py-2 rounded-xl bg-surface-container-lowest text-error font-label-md text-label-md font-semibold hover:bg-error-container transition-colors">
            Kosongkan Keranjang
        </button>
    </form>
@endif