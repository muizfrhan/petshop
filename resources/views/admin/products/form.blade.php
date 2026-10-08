@extends('layouts.admin')

@section('title', $mode === 'create' ? 'Tambah Produk' : 'Edit Produk')
@section('subtitle', $mode === 'create' ? 'Tambahkan produk baru ke katalog' : $product->name)
@section('hideErrors', true)

@section('admin-content')
    @php
        $action = $mode === 'create'
            ? route('admin.products.store')
            : route('admin.products.update', $product);
        $method = $mode === 'create' ? 'POST' : 'PUT';
    @endphp

    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        {{-- Kolom utama --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Informasi Dasar</h2>

                <div class="space-y-4">
                    <x-form-field name="name" label="Nama Produk" required
                                  :value="$product->name"
                                  placeholder="Contoh: Royal Canin Indoor Adult 2kg" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form-field name="sku" label="SKU" :value="$product->sku"
                                      placeholder="Kosongkan untuk dibuat otomatis"
                                      hint="Kode unik produk." />

                        <x-form-field name="slug" label="Slug URL" :value="$product->slug"
                                      placeholder="otomatis-dari-nama"
                                      hint="Dipakai di URL: /produk/{slug}" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form-field name="category_id" label="Kategori" type="select" colspan="">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-form-field>

                        <x-form-field name="brand_id" label="Brand" type="select">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </x-form-field>
                    </div>

                    <x-form-field name="description" label="Deskripsi" type="textarea"
                                  :value="$product->description"
                                  placeholder="Jelaskan manfaat dan komposisi produk..." />

                    <x-form-field name="specification" label="Spesifikasi" type="textarea"
                                  :value="$product->specification"
                                  placeholder="Contoh:&#10;Berat: 2kg&#10;Usia: 12 bulan ke atas" />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Harga &amp; Stok</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-form-field name="price" label="Harga (Rp)" type="number" required
                                  :value="$product->price" placeholder="312000" />

                    <x-form-field name="discount_price" label="Harga Diskon (Rp)" type="number"
                                  :value="$product->discount_price" placeholder="Kosongkan"
                                  hint="Harus lebih kecil dari harga." />

                    <x-form-field name="stock" label="Stok" type="number" required
                                  :value="$product->stock ?? 0" placeholder="0" />

                    <x-form-field name="weight" label="Berat (gram)" type="number"
                                  :value="$product->weight" placeholder="2000" />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Galeri Produk</h2>

                @if ($mode === 'edit' && $product->images->isNotEmpty())
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-5">
                        @foreach ($product->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url() }}" alt="{{ $image->alt }}"
                                     class="w-full aspect-square object-cover rounded-xl bg-surface-container-low border border-surface-outline-app">

                                <label class="absolute top-2 left-2 flex items-center gap-1 px-2 py-1 rounded-full bg-surface-container-lowest/90 text-[10px] font-bold shadow-sm cursor-pointer">
                                    <input type="checkbox" name="existing_images[{{ $image->id }}][is_primary]" value="1"
                                           class="w-3 h-3 accent-primary"
                                           @checked($image->is_primary)>
                                    Utama
                                </label>

                                <label class="absolute top-2 right-2 w-6 h-6 rounded-full bg-error-container flex items-center justify-center cursor-pointer" title="Hapus gambar">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                           class="sr-only image-delete-checkbox">
                                    <span class="material-symbols-outlined text-[14px] text-on-error-container">close</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <p class="font-body-sm text-body-sm text-outline mb-4">
                        Centang <strong>Utama</strong> untuk gambar utama. Klik ikon ✕ untuk menghapus gambar.
                    </p>
                @endif

                <label for="images" class="block cursor-pointer">
                    <div id="dropzone"
                         class="border-2 border-dashed border-surface-outline-app rounded-2xl p-8 text-center hover:border-primary hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[40px] text-outline mb-2">cloud_upload</span>
                        <p class="font-title-md text-title-md text-on-surface font-bold">Klik atau tarik gambar ke sini</p>
                        <p class="font-body-sm text-body-sm text-outline mt-1">JPG, PNG, atau WEBP · Maksimal 2MB per file · Maksimal 6 gambar</p>
                    </div>
                </label>
                <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                       class="sr-only" data-image-input>
                <div id="image-preview" class="grid grid-cols-3 sm:grid-cols-6 gap-3 mt-4"></div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Publikasi</h2>

                <div class="space-y-4">
                    <x-form-field name="status" label="Status" type="select" required>
                        <option value="active" @selected(old('status', $product->status) === 'active')>Aktif</option>
                        <option value="inactive" @selected(old('status', $product->status) === 'inactive')>Nonaktif</option>
                    </x-form-field>

                    <label class="flex items-start gap-3 p-3 rounded-xl bg-surface-container-low cursor-pointer">
                        <input type="checkbox" name="featured" value="1"
                               class="w-5 h-5 rounded border-2 border-surface-outline-app accent-primary mt-0.5"
                               @checked(old('featured', $product->featured))>
                        <span>
                            <span class="font-label-lg text-label-lg font-semibold text-on-surface block">Jadikan Unggulan</span>
                            <span class="font-body-sm text-body-sm text-outline">Tampil di section Produk Unggulan halaman utama.</span>
                        </span>
                    </label>
                </div>
            </div>

            @if ($mode === 'edit')
                <div class="surface-card p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Statistik</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-outline">Terjual</dt>
                            <dd class="font-semibold text-on-surface">{{ number_format($product->sold_count, 0, ',', '.') }} unit</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-outline">Rating</dt>
                            <dd class="font-semibold text-on-surface">{{ number_format($product->rating_average, 1, ',', '.') }} ({{ $product->rating_count }})</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-outline">Dibuat</dt>
                            <dd class="font-semibold text-on-surface">{{ $product->created_at?->format('d/m/Y') }}</dd>
                        </div>
                    </dl>
                </div>
            @endif

            <div class="surface-card p-6 space-y-3">
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $mode === 'create' ? 'Simpan Produk' : 'Simpan Perubahan' }}
                </button>

                <a href="{{ route('admin.products.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
            </div>
        </div>
    </form>
@endsection