@extends('layouts.admin')

@section('title', $mode === 'create' ? 'Buat Kupon' : 'Edit Kupon')
@section('subtitle', $mode === 'create' ? 'Kupon diskon baru' : $coupon->code)
@section('hideErrors', true)

@section('admin-content')
    <form action="{{ $mode === 'create' ? route('admin.coupons.store') : route('admin.coupons.update', $coupon) }}"
          method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Detail Kupon</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="code" label="Kode Kupon" :value="$coupon->code"
                                  placeholder="Contoh: HEMAT10"
                                  hint="Kosongkan untuk membuat kode acak otomatis." />

                    <x-form-field name="name" label="Nama Kupon" required :value="$coupon->name"
                                  placeholder="Contoh: Diskon Pelanggan Baru" />

                    <x-form-field name="type" label="Jenis Diskon" type="select" required>
                        <option value="percent" @selected(old('type', $coupon->type) === 'percent')>Persen (%)</option>
                        <option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Nominal Tetap (Rp)</option>
                    </x-form-field>

                    <x-form-field name="value" label="Nilai Diskon" type="number" required
                                  :value="$coupon->value" min="1" />

                    <x-form-field name="min_purchase" label="Minimum Belanja (Rp)" type="number" required
                                  :value="$coupon->min_purchase ?? 0" min="0"
                                  hint="Isi 0 bila tanpa minimum." />

                    <x-form-field name="quota" label="Batas Penggunaan" type="number"
                                  :value="$coupon->quota" min="1"
                                  hint="Kosongkan bila tanpa batas." />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Masa Berlaku</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="starts_at" label="Tanggal Mulai" type="date"
                                  :value="$coupon->starts_at?->format('Y-m-d')"
                                  hint="Kosongkan untuk langsung aktif." />

                    <x-form-field name="expires_at" label="Tanggal Berakhir" type="date"
                                  :value="$coupon->expires_at?->format('Y-m-d')"
                                  hint="Kosongkan untuk tanpa batas." />
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Status</h2>

                <x-form-field name="status" label="Status Kupon" type="select" required>
                    <option value="active" @selected(old('status', $coupon->status) === 'active')>Aktif</option>
                    <option value="inactive" @selected(old('status', $coupon->status) === 'inactive')>Nonaktif</option>
                </x-form-field>

                @if ($mode === 'edit')
                    <div class="mt-4 p-4 rounded-xl bg-surface-container-low">
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Sudah Digunakan</p>
                        <p class="font-headline-sm text-headline-sm text-on-surface font-bold">
                            {{ $coupon->used_count }} kali
                            @if ($coupon->quota)
                                <span class="font-body-sm text-body-sm text-outline font-normal">/ {{ $coupon->quota }}</span>
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <div class="surface-card p-6 space-y-3">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $mode === 'create' ? 'Simpan Kupon' : 'Simpan Perubahan' }}
                </button>
                <a href="{{ route('admin.coupons.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
            </div>
        </aside>
    </form>
@endsection