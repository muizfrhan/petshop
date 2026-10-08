<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — {{ config('petshop.store.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
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
<body class="bg-surface-container-low font-body-md text-on-surface antialiased selection:bg-primary selection:text-on-primary min-h-screen">

    <a href="#konten-utama"
       class="sr-only focus:not-sr-only focus:absolute focus:z-[70] focus:top-2 focus:left-2 focus:bg-primary focus:text-on-primary focus:px-4 focus:py-2 focus:rounded-xl">
        Lewati ke konten utama
    </a>

    {{-- Backdrop drawer mobile --}}
    <div id="admin-sidebar-backdrop"
         class="fixed inset-0 z-40 bg-inverse-surface/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 hidden lg:hidden"></div>

    @include('layouts.partials.admin-sidebar')

    <div class="lg:pl-[17.5rem] min-h-screen flex flex-col">

        {{-- Topbar admin --}}
        <header class="sticky top-0 z-30 bg-surface/90 backdrop-blur-xl border-b border-surface-outline-app">
            <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center gap-3">
                <button type="button"
                        id="admin-sidebar-open"
                        aria-label="Buka menu navigasi"
                        class="lg:hidden p-2.5 -ml-1 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>

                <nav class="min-w-0 flex items-center gap-1.5" aria-label="Breadcrumb">
                    <span class="hidden sm:inline font-body-sm text-body-sm text-outline shrink-0">Panel Admin</span>
                    <span class="hidden sm:inline material-symbols-outlined text-[16px] text-outline/70">chevron_right</span>
                    <span class="font-label-lg text-label-lg text-on-surface font-semibold truncate">
                        @yield('title', 'Dashboard')
                    </span>
                </nav>

                <div class="ml-auto flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <a href="{{ route('beranda') }}"
                       target="_blank" rel="noopener"
                       class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-surface-outline-app text-on-surface-variant hover:text-on-surface hover:bg-surface-container font-label-md text-label-md transition-colors">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        Lihat Toko
                    </a>

                    <button type="button"
                            data-theme-toggle
                            aria-label="Ganti mode tampilan"
                            class="p-2.5 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-[22px]" data-theme-toggle-icon>light_mode</span>
                    </button>

                    @auth
                        <div class="relative" id="user-dropdown-container">
                            <button type="button"
                                    aria-label="Akun Pengguna"
                                    class="flex items-center gap-2 p-1.5 rounded-full hover:bg-surface-container transition-colors"
                                    id="user-dropdown-btn">
                                @if (auth()->user()->avatar)
                                    <img alt="{{ auth()->user()->name }}"
                                         class="w-8 h-8 rounded-full object-cover"
                                         src="{{ auth()->user()->avatarUrl() }}">
                                @else
                                    <span class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                                    </span>
                                @endif
                                <span class="material-symbols-outlined text-outline text-[18px] hidden sm:block">expand_more</span>
                            </button>

                            <div class="hidden absolute right-0 top-12 w-60 bg-surface-container-lowest rounded-xl border border-surface-outline-app shadow-[0_8px_24px_-4px_rgba(15,23,42,0.14)] py-2 z-50"
                                 id="user-dropdown-menu">
                                <div class="px-4 py-2 border-b border-surface-container-low">
                                    <p class="font-label-lg text-label-lg text-on-surface font-semibold truncate">{{ auth()->user()->name }}</p>
                                    <p class="font-body-sm text-body-sm text-outline">
                                        {{ auth()->user()->isAdmin() ? 'Administrator' : 'Staff' }}
                                    </p>
                                </div>

                                <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                                   href="{{ route('akun.index') }}">
                                    <span class="material-symbols-outlined text-[18px]">account_circle</span> Akun Saya
                                </a>
                                <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                                   href="{{ route('akun.pesanan') }}">
                                    <span class="material-symbols-outlined text-[18px]">receipt_long</span> Riwayat Pesanan
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="border-t border-surface-container-low mt-1 pt-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-secondary hover:bg-secondary-fixed/20 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">logout</span> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        {{-- Konten --}}
        <main id="konten-utama" class="flex-1 min-w-0 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            <div class="min-w-0">

                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div class="min-w-0">
                        <h1 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface font-bold tracking-tight">
                            @yield('title', 'Dashboard')
                        </h1>
                        <p class="font-body-sm text-body-sm text-outline mt-1">
                            @yield('subtitle', 'Panel pengelolaan ' . config('petshop.store.name'))
                        </p>
                    </div>

                    @hasSection('actions')
                        <div class="flex items-center gap-2 flex-wrap shrink-0">@yield('actions')</div>
                    @endif
                </div>

                @if (session('success'))
                    <x-alert variant="success" class="mb-5">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert variant="error" class="mb-5">{{ session('error') }}</x-alert>
                @endif
                @if (session('warning'))
                    <x-alert variant="warning" class="mb-5">{{ session('warning') }}</x-alert>
                @endif

                @if ($errors->any())
                    @hasSection('hideErrors')
                        {{-- Error ditampilkan oleh form masing-masing --}}
                    @else
                        <x-alert variant="error" class="mb-5" title="Periksa kembali isian Anda">
                            <ul class="list-disc list-inside space-y-1 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif
                @endif

                @yield('admin-content')

                <p class="mt-8 pt-6 border-t border-surface-outline-app/70 text-center font-body-sm text-body-sm text-outline">
                    © {{ date('Y') }} {{ config('petshop.store.name') }} · Panel Pengelolaan
                </p>
            </div>
        </main>
    </div>

    <x-toast-container />
    @stack('scripts')
</body>
</html>
