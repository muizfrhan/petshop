@extends('layouts.account')

@section('title', 'Edit Profil')

@section('account-content')
<x-breadcrumb :items="['Akun Saya' => route('akun.index'), 'Edit Profil' => null]" />

<h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mb-8">
    Pengaturan Akun
</h1>

<div class="space-y-6">
    {{-- Profil --}}
    <form action="{{ route('akun.update') }}" method="POST" enctype="multipart/form-data"
          class="surface-card p-6 lg:p-8">
        @csrf
        @method('PUT')

        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">Informasi Profil</h2>
        <p class="font-body-sm text-body-sm text-outline mb-6">
            Perbarui data pribadi dan foto profil Anda.
        </p>

        <div class="flex flex-wrap items-center gap-6 mb-6">
            @if ($user->avatar)
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                     id="avatar-preview"
                     class="w-20 h-20 rounded-2xl object-cover bg-surface-container-low">
            @else
                <div class="w-20 h-20 rounded-2xl bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[36px]">person</span>
                </div>
            @endif

            <div>
                <label for="avatar" class="px-5 py-2.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-bold hover:bg-surface-container-high transition-colors cursor-pointer inline-block">
                    Pilih Foto
                </label>
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp"
                       class="sr-only">
                <p class="font-body-sm text-body-sm text-outline mt-2">
                    JPG, PNG, atau WEBP Â· Maksimal 2MB
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form-field name="name" label="Nama Lengkap" required :value="$user->name" />
            <x-form-field name="phone" label="Nomor WhatsApp" :value="$user->phone"
                          placeholder="08xxxxxxxxxx" />
            <div class="sm:col-span-2">
                <x-form-field name="email" label="Email" type="email" required
                              :value="$user->email"
                              hint="Perubahan email memerlukan verifikasi ulang." />
            </div>
        </div>

        <button type="submit" class="mt-6 px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">save</span>
            Simpan Profil
        </button>
    </form>

    {{-- Password --}}
    <form action="{{ route('akun.password') }}" method="POST" class="surface-card p-6 lg:p-8">
        @csrf
        @method('PUT')

        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">Ubah Password</h2>
        <p class="font-body-sm text-body-sm text-outline mb-6">
            Gunakan password yang kuat dan tidak dipakai di layanan lain.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <x-form-field name="current_password" label="Password Saat Ini" type="password" required
                              autocomplete="current-password" />
            </div>
            <x-form-field name="password" label="Password Baru" type="password" required
                          autocomplete="new-password"
                          hint="Minimal 8 karakter, mengandung huruf dan angka." />
            <x-form-field name="password_confirmation" label="Konfirmasi Password Baru" type="password" required
                          autocomplete="new-password" />
        </div>

        <button type="submit" class="mt-6 px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">lock</span>
            Perbarui Password
        </button>
    </form>

    {{-- Zona bahaya --}}
    @unless ($user->isAdmin())
        <div class="surface-card p-6 lg:p-8 border-error/30">
            <h2 class="font-headline-sm text-headline-sm text-error font-bold mb-1">Hapus Akun</h2>
            <p class="font-body-sm text-body-sm text-outline mb-6">
                Tindakan ini permanen. Akun, wishlist, dan ulasan Anda akan dihapus.
                Pesanan yang sudah selesai tetap tersimpan untuk keperluan laporan.
            </p>

            <button type="button" data-modal-open="delete-account"
                    class="px-6 py-3 rounded-xl bg-error text-on-error font-label-lg text-label-lg font-bold hover:opacity-90 transition-opacity">
                Hapus Akun Saya
            </button>
        </div>
    @endunless
</div>

    <x-modal name="delete-account" title="Hapus Akun" icon="warning">
        <x-alert variant="error" class="mb-5">
            Tindakan ini tidak dapat dibatalkan. Semua data akun Anda akan hilang permanen.
        </x-alert>

        <form action="{{ route('akun.destroy') }}" method="POST" class="space-y-4">
            @csrf
            @method('DELETE')

            <x-form-field name="password" label="Masukkan Password Anda" type="password" required
                          autocomplete="current-password" />

            <button type="submit" class="w-full py-3.5 rounded-xl bg-error text-on-error font-label-lg text-label-lg font-bold hover:opacity-90 transition-opacity">
                Ya, Hapus Akun Saya
            </button>
        </form>
    </x-modal>

@endsection

@push('scripts')
<script>
    document.getElementById('avatar')?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        const preview = document.getElementById('avatar-preview');

        if (file && preview) {
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endpush
