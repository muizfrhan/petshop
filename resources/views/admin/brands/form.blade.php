@extends('layouts.admin')

@section('title', $mode === 'create' ? 'Tambah Brand' : 'Edit Brand')
@section('subtitle', $mode === 'create' ? 'Buat brand baru' : $brand->name)
@section('hideErrors', true)

@section('admin-content')
    <form action="{{ $mode === 'create' ? route('admin.brands.store') : route('admin.brands.update', $brand) }}"
          method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Informasi Brand</h2>

                <div class="space-y-4">
                    <x-form-field name="name" label="Nama Brand" required :value="$brand->name"
                                  placeholder="Contoh: Royal Canin" />

                    <x-form-field name="slug" label="Slug URL" :value="$brand->slug"
                                  placeholder="otomatis-dari-nama" />

                    <x-form-field name="description" label="Deskripsi" type="textarea"
                                  :value="$brand->description"
                                  placeholder="Jelaskan singkat tentang brand ini..." />
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Publikasi</h2>

                <x-form-field name="status" label="Status" type="select" required>
                    <option value="active" @selected(old('status', $brand->status) === 'active')>Aktif</option>
                    <option value="inactive" @selected(old('status', $brand->status) === 'inactive')>Nonaktif</option>
                </x-form-field>
            </div>

            @if ($mode === 'edit')
                <div class="surface-card p-6">
                    <p class="font-label-caps text-label-caps text-outline uppercase mb-2">Jumlah Produk</p>
                    <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $brand->products()->count() }}</p>
                </div>
            @endif

            <div class="surface-card p-6 space-y-3">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $mode === 'create' ? 'Simpan Brand' : 'Simpan Perubahan' }}
                </button>
                <a href="{{ route('admin.brands.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
            </div>
        </aside>
    </form>
@endsection