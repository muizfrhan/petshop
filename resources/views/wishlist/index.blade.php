@extends('layouts.account')

@section('title', 'Wishlist Saya')

@section('account-content')
<x-breadcrumb :items="['Wishlist' => null]" />

<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight">
            Wishlist Saya
        </h1>
        <p class="font-body-sm text-body-sm text-outline mt-1">
            {{ $wishlists->total() }} produk tersimpan
        </p>
    </div>

    @if ($wishlists->isNotEmpty())
        <button type="button"
                id="move-all-to-cart"
                class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
            Pindahkan Semua ke Keranjang
        </button>
    @endif
</div>

@if ($wishlists->isEmpty())
    <x-empty-state
        title="Wishlistmu masih kosong"
        description="Tekan ikon hati pada produk yang Anda sukai untuk menyimpannya di sini."
        icon="favorite"
        :action="route('produk.index')"
        action-label="Jelajahi Produk" />
@else
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach ($wishlists as $wishlist)
            @php $product = $wishlist->product; @endphp

            @if (! $product || ! $product->isActive())
                @continue
            @endif

            <div data-wishlist-item="{{ $product->id }}" class="relative">
                <x-product-card :product="$product" :in-wishlist="true" />

                <button type="button"
                        class="mt-2 w-full py-2 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors"
                        data-wishlist-btn
                        data-product-id="{{ $product->id }}"
                        data-remove-on-unchecked="1"
                        title="Hapus dari wishlist">
                    <span class="material-symbols-outlined text-[16px] align-middle">heart_minus</span>
                    Hapus dari Wishlist
                </button>
            </div>
        @endforeach
    </div>

    <x-pagination :paginator="$wishlists" />
@endif
@endsection

@push('scripts')
<script>
    document.getElementById('move-all-to-cart')?.addEventListener('click', async function () {
        const btn = this;
        const original = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">hourglass_top</span>Memproses...';

        try {
            const { data } = await window.axios.post('/wishlist/ke-keranjang', {});

            let message = data.message;
            if (data.skipped?.length) {
                message += ' Dilewati: ' + data.skipped.join(', ') + '.';
            }

            window.pchToast(message, data.count > 0 ? 'success' : 'info');
            await window.pchRefreshCart();

            if (data.count > 0) {
                setTimeout(() => (window.location.reload()), 1200);
            }
        } catch (error) {
            window.pchToast(error.response?.data?.message || 'Gagal memindahkan wishlist.', 'error');
            btn.disabled = false;
            btn.innerHTML = original;
        }
    });
</script>
@endpush
