@extends('layouts.admin')

@section('title', $product->name)
@section('subtitle', 'SKU ' . $product->sku)

@section('admin-content')
    @php
        $image = $product->primaryImage->first();
        $imageUrl = $image
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($image->path)
            : asset('images/hero-card.jpg');
        $unitsSold = (int) $product->orderItems->sum('quantity');
        $revenue = (int) $product->orderItems->sum('subtotal');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <div class="flex flex-col sm:flex-row gap-6">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                         class="w-full sm:w-56 h-56 object-cover rounded-2xl bg-surface-container-low shrink-0">

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span @class([
                                'badge',
                                'bg-tertiary-fixed text-tertiary-container' => $product->status === 'active',
                                'bg-surface-container text-outline' => $product->status !== 'active',
                            ])>{{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>

                            @if ($product->featured)
                                <span class="badge bg-primary-fixed text-on-primary-fixed-variant">Unggulan</span>
                            @endif

                            @if ($product->isOnSale())
                                <span class="badge bg-error-container text-on-error-container">
                                    Diskon {{ $product->discountPercent() }}%
                                </span>
                            @endif

                            @if ($product->isOutOfStock())
                                <span class="badge bg-error-container text-on-error-container">Stok Habis</span>
                            @elseif ($product->isLowStock())
                                <span class="badge bg-secondary-fixed text-on-secondary-fixed-variant">
                                    Stok Menipis ({{ $product->stock }})
                                </span>
                            @endif
                        </div>

                        <h1 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-2">{{ $product->name }}</h1>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 font-label-md text-label-md text-outline mb-4">
                            <span>{{ $product->category?->name ?? 'Tanpa kategori' }}</span>
                            <span>{{ $product->brand?->name ?? 'Tanpa brand' }}</span>
                            @if ($product->weight)
                                <span>Berat {{ $product->weight }} gr</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-end gap-3 mb-5">
                            @if ($product->isOnSale())
                                <span class="font-headline text-headline text-primary font-bold">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                </span>
                                <span class="font-body-sm text-body-sm text-outline line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="font-headline text-headline text-primary font-bold">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="px-5 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                Edit Produk
                            </a>
                            <a href="{{ route('produk.show', $product->slug) }}" target="_blank" rel="noopener"
                               class="px-5 py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                Lihat di Situs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Deskripsi</h2>
                <div class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $product->description ?: 'Belum ada deskripsi.' }}</div>

                @if ($product->specification)
                    <h3 class="font-title-md text-title-md text-on-surface font-bold mt-6 mb-3">Spesifikasi</h3>
                    <div class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $product->specification }}</div>
                @endif
            </div>

            <div class="surface-card overflow-hidden">
                <div class="px-6 py-5 border-b border-surface-container-low flex items-center justify-between">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">
                        Ulasan ({{ $product->allReviews->count() }})
                    </h2>
                    <x-rating :rating="$product->rating_average" :count="$product->rating_count" />
                </div>

                @if ($product->allReviews->isEmpty())
                    <x-empty-state title="Belum ada ulasan" description="Pelanggan belum memberikan ulasan untuk produk ini."
                                   icon="reviews" />
                @else
                    <div class="divide-y divide-surface-container-low">
                        @foreach ($product->allReviews->take(10) as $review)
                            <div class="p-5 flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-3 mb-1.5">
                                        <span class="font-body-sm text-body-sm text-on-surface font-semibold">
                                            {{ $review->user?->name ?? 'Pengguna dihapus' }}
                                        </span>
                                        <x-rating :rating="$review->rating" :count="null" :showCount="false" />
                                        <span @class([
                                            'badge',
                                            'bg-tertiary-fixed text-tertiary-container' => $review->status === 'published',
                                            'bg-secondary-fixed text-on-secondary-fixed-variant' => $review->status === 'pending',
                                            'bg-surface-container text-outline' => $review->status === 'hidden',
                                        ])>{{ $review->status }}</span>
                                        <span class="font-label-md text-label-md text-outline">
                                            {{ $review->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                        "{{ $review->comment }}"
                                    </p>
                                </div>

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="shrink-0"
                                      onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Statistik</h2>

                <dl class="space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="font-body-sm text-body-sm text-outline">Terjual</dt>
                        <dd class="font-title-md text-title-md text-on-surface font-bold">{{ $unitsSold }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-body-sm text-body-sm text-outline">Pendapatan</dt>
                        <dd class="font-title-md text-title-md text-primary font-bold">
                            Rp {{ number_format($revenue, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-body-sm text-body-sm text-outline">Stok</dt>
                        <dd class="font-title-md text-title-md text-on-surface font-bold">{{ $product->stock }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-body-sm text-body-sm text-outline">Di Wishlist</dt>
                        <dd class="font-title-md text-title-md text-on-surface font-bold">
                            {{ $product->wishlists()->count() }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-body-sm text-body-sm text-outline">Rating</dt>
                        <dd class="font-title-md text-title-md text-on-surface font-bold">
                            {{ number_format($product->rating_average, 1, ',', '.') }}
                            <span class="font-body-sm text-body-sm text-outline font-normal">({{ $product->rating_count }})</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Informasi</h2>

                <dl class="space-y-3">
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">SKU</dt>
                        <dd class="font-mono font-body-sm text-body-sm text-on-surface">{{ $product->sku }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Slug</dt>
                        <dd class="font-mono font-body-sm text-body-sm text-on-surface break-all">{{ $product->slug }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Diperbarui</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">{{ $product->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <a href="{{ route('admin.products.index') }}"
               class="w-full py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                Kembali ke Daftar Produk
            </a>
        </aside>
    </div>
@endsection