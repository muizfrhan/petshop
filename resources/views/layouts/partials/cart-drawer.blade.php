<div class="fixed inset-0 bg-inverse-surface/40 backdrop-blur-sm z-50 hidden transition-opacity opacity-0" id="cart-drawer-backdrop"></div>

<aside class="fixed top-0 right-0 bottom-0 w-96 max-w-[90vw] bg-surface z-[60] translate-x-full transition-transform duration-300 ease-in-out flex flex-col shadow-[0_20px_32px_-6px_rgba(15,23,42,0.12)]"
       id="cart-drawer"
       aria-label="Keranjang belanja"
       aria-hidden="true">

    <div class="h-20 px-6 flex items-center justify-between bg-surface-container-low">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[22px]">shopping_bag</span>
            <span class="font-title-md text-title-md text-on-surface font-bold">Keranjang Belanja</span>
            <span class="font-label-md text-label-md px-2 py-0.5 rounded-full bg-primary/10 text-primary font-bold"
                  data-cart-count-label @if (($cartCount ?? 0) === 0) hidden @endif>
                {{ $cartCount ?? 0 }} Item
            </span>
        </div>
        <button class="p-2 text-on-surface-variant hover:text-on-surface rounded-xl" id="cart-drawer-close" type="button" aria-label="Tutup keranjang">
            <span class="material-symbols-outlined text-[22px]">close</span>
        </button>
    </div>

    {{-- Isi dirender server-side, lalu di-refresh via AJAX setelah add/update/remove --}}
    <div class="flex-1 p-6 space-y-4 overflow-y-auto" id="cart-drawer-items">
        @include('layouts.partials.cart-items', ['cartItems' => $cartItems ?? collect()])
    </div>

    <div class="p-6 bg-surface-container-low space-y-4 border-t border-surface-container-lowest">
        <div class="space-y-1.5" id="cart-drawer-summary">
            @include('layouts.partials.cart-summary', ['totals' => $cartTotals ?? null])
        </div>

        <div class="flex flex-col gap-2">
            <a href="{{ route('checkout.create') }}"
               class="w-full flex items-center justify-center py-3 bg-secondary text-on-primary rounded-xl font-label-lg text-label-lg hover:bg-secondary/90 shadow-md transition-all"
               data-cart-checkout-link>
                Lanjut ke Pembayaran
            </a>
            <a href="{{ route('keranjang.index') }}"
               class="w-full flex items-center justify-center py-2.5 bg-surface-container-lowest text-on-surface rounded-xl font-label-lg text-label-lg hover:bg-surface-variant transition-colors">
                Lihat Keranjang Lengkap
            </a>
        </div>
    </div>
</aside>