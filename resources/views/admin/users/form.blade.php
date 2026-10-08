@extends('layouts.admin')

@section('title', 'Edit ' . $user->name)
@section('subtitle', $user->email)
@section('hideErrors', true)

@section('admin-content')
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @method('PUT')

        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Informasi Akun</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="name" label="Nama Lengkap" required :value="$user->name" />
                    <x-form-field name="phone" label="Nomor Telepon" :value="$user->phone" />
                    <div class="sm:col-span-2">
                        <x-form-field name="email" label="Email" type="email" required :value="$user->email" />
                    </div>
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">Reset Password</h2>
                <p class="font-body-sm text-body-sm text-outline mb-5">
                    Kosongkan bila tidak ingin mengubah password pengguna ini.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="password" label="Password Baru" type="password"
                                  autocomplete="new-password"
                                  hint="Minimal 8 karakter, mengandung huruf dan angka." />
                    <x-form-field name="password_confirmation" label="Konfirmasi Password" type="password"
                                  autocomplete="new-password" />
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Akses</h2>

                <div class="space-y-4">
                    <x-form-field name="role" label="Peran" type="select" required>
                        <option value="customer" @selected(old('role', $user->role) === 'customer')>Pelanggan</option>
                        <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrator</option>
                    </x-form-field>

                    <x-form-field name="status" label="Status" type="select" required>
                        <option value="active" @selected(old('status', $user->status) === 'active')>Aktif</option>
                        <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Nonaktif</option>
                    </x-form-field>
                </div>

                <div class="mt-5 p-3 rounded-xl bg-surface-container-low">
                    <p class="font-label-md text-label-md font-bold text-on-surface mb-1">Statistik</p>
                    <p class="font-body-sm text-body-sm text-outline">
                        {{ $user->orders()->count() }} pesanan ·
                        {{ $user->reviews()->count() }} ulasan ·
                        {{ $user->wishlists()->count() }} wishlist
                    </p>
                </div>
            </div>

            <div class="surface-card p-6 space-y-3">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
            </div>
        </aside>
    </form>
@endsection