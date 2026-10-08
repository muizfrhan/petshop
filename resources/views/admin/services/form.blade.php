@extends('layouts.admin')

@section('title', $mode === 'create' ? 'Tambah Layanan' : 'Edit Layanan')
@section('subtitle', $mode === 'create' ? 'Buat layanan perawatan baru' : $service->name)
@section('hideErrors', true)

@section('admin-content')
    <form action="{{ $mode === 'create' ? route('admin.services.store') : route('admin.services.update', $service) }}"
          method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Informasi Layanan</h2>

                <div class="space-y-4">
                    <x-form-field name="name" label="Nama Layanan" required :value="$service->name"
                                  placeholder="Contoh: Grooming Salon & Spa" />

                    <x-form-field name="slug" label="Slug URL" :value="$service->slug"
                                  placeholder="otomatis-dari-nama" />

                    <x-form-field name="description" label="Deskripsi" type="textarea" required
                                  :value="$service->description"
                                  placeholder="Jelaskan layanan ini secara singkat..." />

                    <div>
                        <label class="form-label" for="includes">Yang Termasuk</label>
                        <textarea id="includes" name="includes" rows="6" class="form-input py-3"
                                  placeholder="Satu baris per item, contoh:&#10;Mandi antiseptic&#10;Potong kuku&#10;Pembersihan telinga">{{ old('includes', $service->includes) }}</textarea>
                        <p class="mt-1.5 font-body-sm text-body-sm text-outline">Satu item per baris.</p>
                    </div>

                    <x-form-field name="icon" label="Ikon Material Symbols"
                                  :value="$service->icon" placeholder="Contoh: shower, medical_services, vaccines" />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Harga &amp; Durasi</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="price" label="Harga (Rp)" type="number" required
                                  :value="$service->price" placeholder="65000" />

                    <x-form-field name="duration_minutes" label="Durasi (menit)" type="number" required
                                  :value="$service->duration_minutes" placeholder="90"
                                  hint="Contoh: 60 = 1 jam, 1440 = harian." />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Gambar Layanan</h2>

                <label for="image" class="block cursor-pointer">
                    <div class="border-2 border-dashed border-surface-outline-app rounded-2xl p-8 text-center hover:border-primary hover:bg-surface-container-low transition-colors">
                        @if ($service->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($service->image) }}"
                                 alt="{{ $service->name }}"
                                 class="w-24 h-24 object-cover rounded-2xl mx-auto mb-3">
                        @else
                            <span class="material-symbols-outlined text-[40px] text-outline mb-2">image</span>
                        @endif
                        <p class="font-title-md text-title-md text-on-surface font-bold">
                            {{ $service->image ? 'Ganti Gambar' : 'Klik untuk Pilih Gambar' }}
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
                        <option value="active" @selected(old('status', $service->status) === 'active')>Aktif</option>
                        <option value="inactive" @selected(old('status', $service->status) === 'inactive')>Nonaktif</option>
                    </x-form-field>

                    <x-form-field name="sort_order" label="Urutan Tampil" type="number" required
                                  :value="$service->sort_order ?? 0" />

                    <label class="flex items-start gap-3 p-3 rounded-xl bg-surface-container-low cursor-pointer">
                        <input type="checkbox" name="featured" value="1"
                               class="w-5 h-5 rounded border-2 border-surface-outline-app accent-primary mt-0.5"
                               @checked(old('featured', $service->featured))>
                        <span>
                            <span class="font-label-lg text-label-lg font-semibold text-on-surface block">Jadikan Unggulan</span>
                            <span class="font-body-sm text-body-sm text-outline">Tampil di section layanan homepage.</span>
                        </span>
                    </label>
                </div>
            </div>

            @if ($mode === 'edit')
                <div class="surface-card p-6">
                    <p class="font-label-caps text-label-caps text-outline uppercase mb-2">Total Booking</p>
                    <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $service->bookings()->count() }}</p>
                </div>
            @endif

            <div class="surface-card p-6 space-y-3">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $mode === 'create' ? 'Simpan Layanan' : 'Simpan Perubahan' }}
                </button>
                <a href="{{ route('admin.services.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
            </div>
        </aside>
    </form>
@endsection