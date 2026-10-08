@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('admin-content')
    {{-- Filter & search --}}
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div class="lg:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari nama produk atau SKU..."
                       class="form-input">
            </div>

            <select name="kategori" class="form-input">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(($filters['kategori'] ?? '') === $category->slug)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="brand" class="form-input">
                <option value="">Semua Brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->slug }}" @selected(($filters['brand'] ?? '') === $brand->slug)>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
            </select>

            <select name="stok" class="form-input">
                <option value="">Semua Stok</option>
                <option value="habis" @selected(($filters['stok'] ?? '') === 'habis')>Stok Habis</option>
                <option value="menipis" @selected(($filters['stok'] ?? '') === 'menipis')>Menipis</option>
                <option value="tersedia" @selected(($filters['stok'] ?? '') === 'tersedia')>Tersedia</option>
            </select>
        </div>

        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan Filter
            </button>
            @if (array_filter($filters))
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">
                {{ $products->total() }} produk
            </span>
        </div>
    </form>

    {{-- Tabel produk --}}
    <div class="surface-card overflow-hidden">
        @if ($products->isEmpty())
            <x-empty-state
                title="Produk tidak ditemukan"
                description="Coba ubah filter pencarian Anda."
                icon="search_off"
                :action="route('admin.products.index')"
                action-label="Reset Filter" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                                             class="w-11 h-11 rounded-lg object-cover bg-surface-container-low shrink-0" loading="lazy">
                                        <div class="min-w-0">
                                            <p class="font-body-sm text-body-sm text-on-surface font-semibold line-clamp-1">
                                                {{ $product->name }}
                                            </p>
                                            <p class="font-label-md text-label-md text-outline line-clamp-1">
                                                {{ $product->brand?->name ?? 'Tanpa brand' }}
                                                @if ($product->featured)
                                                    · <span class="text-primary font-semibold">Unggulan</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">{{ $product->sku }}</span>
                                </td>
                                <td>
                                    <span class="font-body-sm text-body-sm text-on-surface">
                                        {{ $product->category?->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-primary">
                                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                    </span>
                                    @if ($product->isOnSale())
                                        <p class="font-label-md text-label-md text-outline line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-error-container text-on-error-container' => $product->isOutOfStock(),
                                        'bg-secondary-fixed text-on-secondary-fixed-variant' => ! $product->isOutOfStock() && $product->isLowStock(),
                                        'bg-surface-container text-on-surface-variant' => ! $product->isLowStock(),
                                    ])>{{ $product->stock }}</span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $product->status === 'active',
                                        'bg-surface-container text-outline' => $product->status !== 'active',
                                    ])>{{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors"
                                           title="Lihat">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors"
                                           title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                              onsubmit="return confirm('Hapus produk {{ $product->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-lg text-error hover:bg-error-container transition-colors"
                                                    title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$products" />
                </div>
            @endif
        @endif
    </div>
@endsection