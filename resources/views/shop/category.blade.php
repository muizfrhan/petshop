@extends('layouts.app')

@section('title', $category->name)

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="[
                'Produk' => route('produk.index'),
                $category->name => null,
            ]" />

            <div class="flex items-start gap-4">
                @if ($category->icon)
                    <div class="w-14 h-14 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[28px]">{{ $category->icon }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight">
                        {{ $category->name }}
                    </h1>
                    @if ($category->description)
                        <p class="font-body-sm text-body-sm text-outline mt-1 max-w-2xl">{{ $category->description }}</p>
                    @endif
                    <p class="font-body-sm text-body-sm text-outline mt-2">{{ $products->total() }} produk tersedia</p>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            @if ($products->isEmpty())
                <x-empty-state
                    title="Belum ada produk di kategori ini"
                    description="Pilih kategori lain atau lihat seluruh produk yang tersedia."
                    icon="inventory_2"
                    :action="route('produk.index')"
                    action-label="Lihat Semua Produk" />
            @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" :in-wishlist="in_array($product->id, $wishlistIds)" />
                    @endforeach
                </div>

                <x-pagination :paginator="$products" />
            @endif
        </div>
    </section>
</main>
@endsection