@extends('layouts.app')

@section('title', 'Atur Ulang Password')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-16">
        <div class="max-w-md mx-auto px-6">
            <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px]">password</span>
            </div>

            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight text-center mb-2">
                Atur Ulang Password
            </h1>
            <p class="font-body-md text-body-md text-outline text-center mb-8 leading-relaxed">
                Masukkan password baru untuk akun Anda.
            </p>

            <form action="{{ route('password.update') }}" method="POST" class="surface-card p-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <x-form-field name="email" label="Email" type="email" required
                              :value="old('email', $email)" autocomplete="email" />

                <x-form-field name="password" label="Password Baru" type="password" required
                              autocomplete="new-password"
                              hint="Minimal 8 karakter, mengandung huruf dan angka." />

                <x-form-field name="password_confirmation" label="Konfirmasi Password" type="password" required
                              autocomplete="new-password" />

                <button type="submit" class="w-full py-4 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all shadow-md">
                    Perbarui Password
                </button>
            </form>
        </div>
    </section>
</main>
@endsection