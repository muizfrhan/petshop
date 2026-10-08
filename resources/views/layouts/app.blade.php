<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('petshop.store.name')) — {{ config('petshop.store.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Terapkan tema sebelum render agar tidak ada kedipan (FOUC)
        (function () {
            try {
                if (localStorage.getItem('pch-theme') === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>

    @stack('head')
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-primary selection:text-on-primary min-h-screen flex flex-col">

    <a href="#konten-utama" class="sr-only focus:not-sr-only focus:absolute focus:z-[60] focus:top-2 focus:left-2 focus:bg-primary focus:text-on-primary focus:px-4 focus:py-2 focus:rounded-xl">
        Lewati ke konten utama
    </a>

    @include('layouts.partials.navbar')

    @yield('content')

    @include('layouts.partials.footer')

    <x-toast-container />
    @stack('scripts')
</body>
</html>