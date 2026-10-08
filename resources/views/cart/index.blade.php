@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="['Keranjang' => null]" />

            <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mb-8">
                Keranjang Belanja
            </h1>

            @if ($items->isEmpty())
                <x-empty-state
                    title="Keranjangmu masih kosong"
                    description="Yuk, temukan produk untuk sahabat berbulu kamu sebelum keranjang terisi."
                    icon="shopping_cart"
                    :action="route('produk.index')"
                    action-label="Mulai Belanja" />
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Daftar item --}}
                    <div class="lg:col-span-2 space-y-4">
                        @foreach ($items as $item)
                            @php
                                $product = $item->product;
                                $price = $product->final_price;
                                $exceeds = $product->stock < $item->quantity;
                            @endphp
                            <div @class([
                                'surface-card p-4 flex gap-4',
                                'ring-2 ring-error' => $exceeds,
                            ])>
                                <a href="{{ route('produk.show', $product->slug) }}" class="shrink-0">
                                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                                         class="w-24 h-24 rounded-xl object-cover bg-surface-container-low" loading="lazy">
                                </a>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div class="min-w-0">
                                            <h3 class="font-title-md text-title-md text-on-surface font-bold line-clamp-2">
                                                <a href="{{ route('produk.show', $product->slug) }}" class="hover:text-primary">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                            <p class="font-body-sm text-body-sm text-outline mt-0.5">
                                                {{ $product->brand?->name ?? 'Tanpa brand' }} · SKU {{ $product->sku }}
                                            </p>
                                        </div>

                                        <form action="{{ route('keranjang.destroy', $item) }}" method="POST" class="shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors"
                                                    aria-label="Hapus {{ $product->name }}">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>

                                    @if ($exceeds)
                                        <p class="flex items-center gap-1.5 font-body-sm text-body-sm text-error mb-2">
                                            <span class="material-symbols-outlined text-[16px]">warning</span>
                                            Stok tersisa {{ $product->stock }} unit. Kurangi jumlah untuk melanjutkan.
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <form action="{{ route('keranjang.update', $item) }}" method="POST"
                                              class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center bg-surface-container-low rounded-xl border border-surface-outline-app">
                                                <button type="submit" name="quantity" value="{{ max(0, $item->quantity - 1) }}"
                                                        class="p-2.5 rounded-l-xl hover:bg-surface-container-lowest transition-colors"
                                                        aria-label="Kurangi jumlah">
                                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                                </button>
                                                <span class="w-12 text-center font-title-md text-title-md font-bold text-on-surface">
                                                    {{ $item->quantity }}
                                                </span>
                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                        class="p-2.5 rounded-r-xl hover:bg-surface-container-lowest transition-colors disabled:opacity-40"
                                                        @disabled($product->stock <= $item->quantity)
                                                        aria-label="Tambah jumlah">
                                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                                </button>
                                            </div>
                                        </form>

                                        <div class="text-right">
                                            <p class="font-headline-sm text-headline-sm text-primary font-bold">
                                                Rp {{ number_format($price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                            <p class="font-body-sm text-body-sm text-outline">
                                                Rp {{ number_format($price, 0, ',', '.') }} × {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <a href="{{ route('produk.index') }}"
                               class="inline-flex items-center gap-2 font-label-lg text-label-lg font-semibold text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                                Lanjut Belanja
                            </a>

                            <form action="{{ route('keranjang.clear') }}" method="POST"
                                  onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-label-md text-label-md font-semibold text-error hover:underline">
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Ringkasan --}}
                    <aside class="lg:col-span-1">
                        <div class="surface-card p-6 lg:sticky lg:top-24">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Ringkasan Belanja</h2>

                            {{-- Kupon --}}
                            <form action="{{ route('keranjang.kupon') }}" method="POST" class="mb-5">
                                @csrf
                                <label class="form-label" for="coupon_code">Kode Kupon</label>
                                <div class="flex gap-2">
                                    <input type="text" id="coupon_code" name="coupon_code"
                                           value="{{ $coupon?->code ?? old('coupon_code') }}"
                                           placeholder="Contoh: PETHEMAT30" class="form-input">
                                    <button type="submit" class="px-4 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-bold hover:bg-surface-container-high transition-colors shrink-0">
                                        Pakai
                                    </button>
                                </div>
                            </form>

                            <dl class="space-y-2.5 pb-5 border-b border-surface-container-low">
                                <div class="flex justify-between font-body-sm text-body-sm">
                                    <dt class="text-outline">Subtotal ({{ $items->sum('quantity') }} item)</dt>
                                    <dd class="text-on-surface font-semibold">Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</dd>
                                </div>
                                @if ($totals['discount'] > 0)
                                    <div class="flex justify-between font-body-sm text-body-sm">
                                        <dt class="text-primary font-semibold">Diskon {{ $coupon?->code }}</dt>
                                        <dd class="text-primary font-semibold">- Rp {{ number_format($totals['discount'], 0, ',', '.') }}</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between font-body-sm text-body-sm">
                                    <dt class="text-outline">Ongkos kirim</dt>
                                    <dd class="text-surface-tint font-semibold">Dihitung saat checkout</dd>
                                </div>
                            </dl>

                            <div class="flex justify-between items-baseline mt-5 mb-1">
                                <span class="font-label-lg text-label-lg font-semibold text-on-surface">Total</span>
                                <span class="font-headline-sm text-headline-sm text-primary font-bold">
                                    Rp {{ number_format($totals['subtotal'] - $totals['discount'], 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="font-body-sm text-body-sm text-outline mb-5">
                                Ongkir dan pajak dihitung ulang di halaman checkout.
                            </p>

                            <a href="{{ route('checkout.create') }}"
                               class="w-full py-4 rounded-xl bg-secondary text-on-primary font-headline-sm text-headline-sm font-bold hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 shadow-md">
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </section>
</main>
@endsection