@extends('layouts.admin')

@section('title', $mode === 'create' ? 'Tambah Kategori' : 'Edit Kategori')
@section('subtitle', $mode === 'create' ? 'Buat kategori produk baru' : $category->name)
@section('hideErrors', true)

@section('admin-content')
    <form action="{{ $mode === 'create' ? route('admin.categories.store') : route('admin.categories.update', $category) }}"
          method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Informasi Kategori</h2>

                <div class="space-y-4">
                    <x-form-field name="name" label="Nama Kategori" required :value="$category->name"
                                  placeholder="Contoh: Makanan Kucing" />

                    <x-form-field name="slug" label="Slug URL" :value="$category->slug"
                                  placeholder="otomatis-dari-nama"
                                  hint="Dipakai di URL: /kategori/{slug}" />

                    <x-form-field name="description" label="Deskripsi" type="textarea"
                                  :value="$category->description"
                                  placeholder="Jelaskan singkat kategori ini..." />

                    <x-form-field name="icon" label="Ikon Material Symbols"
                                  :value="$category->icon" placeholder="Contoh: pets, restaurant, vaccines"
                                  hint="Nama ikon dari Google Material Symbols." />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Gambar Kategori</h2>

                <label for="image" class="block cursor-pointer">
                    <div class="border-2 border-dashed border-surface-outline-app rounded-2xl p-8 text-center hover:border-primary hover:bg-surface-container-low transition-colors">
                        @if ($category->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-24 h-24 object-cover rounded-2xl mx-auto mb-3">
                        @else
                            <span class="material-symbols-outlined text-[40px] text-outline mb-2">image</span>
                        @endif
                        <p class="font-title-md text-title-md text-on-surface font-bold">
                            {{ $category->image ? 'Ganti Gambar' : 'Klik untuk Pilih Gambar' }}
                        </p>
                        <p class="font-body-sm text-body-sm text-outline mt-1">JPG, PNG, atau WEBP · Maksimal 2MB</p>
                    </div>
                </label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only">
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Publikasi</h2>

                <div class="space-y-4">
                    <x-form-field name="status" label="Status" type="select" required>
                        <option value="active" @selected(old('status', $category->status) === 'active')>Aktif</option>
                        <option value="inactive" @selected(old('status', $category->status) === 'inactive')>Nonaktif</option>
                    </x-form-field>

                    <x-form-field name="sort_order" label="Urutan Tampil" type="number" required
                                  :value="$category->sort_order ?? 0"
                                  hint="Angka kecil tampil lebih dulu." />
                </div>
            </div>

            @if ($mode === 'edit')
                <div class="surface-card p-6">
                    <p class="font-label-caps text-label-caps text-outline uppercase mb-2">Jumlah Produk</p>
                    <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $category->products()->count() }}</p>
                </div>
            @endif

            <div class="surface-card p-6 space-y-3">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $mode === 'create' ? 'Simpan Kategori' : 'Simpan Perubahan' }}
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
            </div>
        </aside>
    </form>
@endsection