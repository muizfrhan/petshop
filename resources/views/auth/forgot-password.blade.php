@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-16">
        <div class="max-w-md mx-auto px-6">
            <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px]">lock_reset</span>
            </div>

            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight text-center mb-2">
                Lupa Password
            </h1>
            <p class="font-body-md text-body-md text-outline text-center mb-8 leading-relaxed">
                Masukkan email yang terdaftar. Kami akan mengirim tautan untuk mengatur ulang password Anda.
            </p>

            @if (session('success'))
                <x-alert variant="success" class="mb-5">{{ session('success') }}</x-alert>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="surface-card p-6 space-y-4">
                @csrf

                <x-form-field name="email" label="Email" type="email" required
                              :value="old('email')" placeholder="nama@email.com"
                              autocomplete="email" />

                <button type="submit" class="w-full py-4 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all shadow-md">
                    Kirim Tautan Reset
                </button>
            </form>

            <p class="font-body-sm text-body-sm text-outline text-center mt-6">
                Ingat password Anda?
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Kembali ke halaman masuk</a>
            </p>
        </div>
    </section>
</main>
@endsection