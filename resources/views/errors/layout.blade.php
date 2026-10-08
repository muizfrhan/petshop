<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — {{ config('petshop.store.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex items-center justify-center px-6 py-12">

<div class="w-full max-w-2xl text-center">

    <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 mb-10 group">
        <img alt="Logo" class="h-9 w-auto object-contain" src="{{ asset('images/logo.png') }}">
        <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ config('petshop.store.name') }}</span>
    </a>

    <div class="w-24 h-24 rounded-3xl bg-surface-container-low flex items-center justify-center mx-auto mb-8">
        <span class="material-symbols-outlined text-[48px] text-primary">@yield('icon', 'pets')</span>
    </div>

    <p class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase mb-3">
        @yield('code')
    </p>

    <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mb-4">
        @yield('title', 'Terjadi Kesalahan')
    </h1>

    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto mb-10 leading-relaxed">
        @yield('message', 'Terjadi kesalahan yang tidak terduga. Silakan coba beberapa saat lagi.')
    </p>

    @yield('actions')
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('beranda') }}"
               class="px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all inline-flex items-center justify-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">home</span>
                Kembali ke Beranda
            </a>
            <a href="{{ route('produk.index') }}"
               class="px-7 py-3.5 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-highest transition-all inline-flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">storefront</span>
                Lihat Produk
            </a>
        </div>
    @endyield

    <p class="font-body-sm text-body-sm text-outline mt-10">
        Butuh bantuan?
        <a href="https://wa.me/{{ config('petshop.store.whatsapp') }}" target="_blank" rel="noopener"
           class="text-primary font-semibold hover:underline">Hubungi kami via WhatsApp</a>
    </p>
</div>

</body>
</html>