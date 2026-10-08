@props([
    'active' => null,
    'cartCount' => 0,
    'wishlistCount' => 0,
])

@php
    $user = auth()->user();
    $navItems = [
        ['label' => 'Beranda', 'route' => 'beranda'],
        ['label' => 'Produk', 'route' => 'produk.index'],
        ['label' => 'Layanan', 'route' => 'layanan.index'],
        ['label' => 'Artikel', 'route' => 'artikel.index'],
        ['label' => 'Tentang Kami', 'route' => 'tentang-kami'],
        ['label' => 'Kontak', 'route' => 'kontak'],
    ];
@endphp

<header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-20 max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between gap-space-sm">
        <div class="flex items-center gap-space-sm md:gap-space-md shrink-0">
            <button aria-label="Buka Menu Mobile"
                    class="xl:hidden p-2 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors"
                    id="btn-mobile-menu-open" type="button">
                <span class="material-symbols-outlined text-[26px]">menu</span>
            </button>

            <a class="flex items-center gap-space-xs" href="{{ route('beranda') }}" aria-label="{{ config('petshop.store.name') }} - Beranda">
                <img alt="{{ config('petshop.store.name') }} Logo"
                     class="h-7 sm:h-8 w-auto object-contain"
                     src="{{ asset('images/logo.png') }}">
            </a>
        </div>

        {{-- Pencarian: suggestions diambil dari database via AJAX --}}
        <div class="relative flex-1 min-w-[260px] max-w-lg hidden md:block">
            <label class="sr-only" for="desktop-search-input">Cari produk</label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">search</span>
                <input class="w-full h-11 pl-11 pr-10 rounded-xl bg-surface-container-low border border-surface-outline-app/80 text-on-surface font-body-sm text-body-sm placeholder:text-outline/90 focus:outline-none focus:bg-surface-container-lowest focus:border-primary/40 focus:ring-2 focus:ring-primary/15 transition-all"
                       id="desktop-search-input"
                       name="q"
                       placeholder="Cari makanan, vitamin, aksesoris..."
                       type="search"
                       value="{{ request('q') }}"
                       autocomplete="off"
                       role="combobox"
                       aria-autocomplete="list"
                       aria-expanded="false"
                       aria-controls="desktop-search-dropdown"
                       data-search-url="{{ route('produk.index') }}"
                       data-live-search="desktop-search-dropdown">
                <button class="hidden absolute right-2 p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors"
                        id="desktop-search-clear" data-search-clear type="button" aria-label="Bersihkan pencarian">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
            <div class="hidden absolute left-0 right-0 top-14 bg-surface-container-lowest rounded-2xl border border-surface-outline-app shadow-[0_16px_40px_-12px_rgba(15,23,42,0.18)] p-1.5 z-50 overflow-hidden max-h-[26rem] overflow-y-auto"
                 id="desktop-search-dropdown"
                 role="listbox"></div>
        </div>

        <nav class="hidden xl:flex items-center gap-space-2xs shrink-0">
            @foreach ($navItems as $item)
                @php $isActive = $active === $item['route']; @endphp
                <a @class([
                    'px-2.5 py-2 whitespace-nowrap transition-all rounded-xl font-label-lg text-label-lg',
                    'bg-primary-container text-on-primary-container font-semibold' => $isActive,
                    'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' => ! $isActive,
                ]) @if($isActive) aria-current="page" @endif
                   href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-space-xs shrink-0">
            @auth
                <a aria-label="Wishlist Saya"
                   class="relative p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                   href="{{ route('wishlist.index') }}"
                   data-wishlist-toggle>
                    <span class="material-symbols-outlined text-[22px]">favorite</span>
                    <span class="absolute top-1 right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 bg-secondary text-on-secondary rounded-full font-label-caps text-[10px] font-bold leading-none"
                          data-wishlist-badge @if($wishlistCount === 0) hidden @endif>{{ $wishlistCount }}</span>
                </a>
            @else
                <a aria-label="Wishlist Saya"
                   class="relative p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                   href="{{ route('login') }}" title="Masuk untuk menggunakan wishlist">
                    <span class="material-symbols-outlined text-[22px]">favorite</span>
                </a>
            @endauth

            <button aria-label="Keranjang Belanja"
                    class="relative p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                    id="cart-toggle-btn" type="button">
                <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
                <span class="absolute top-1 right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 bg-primary text-on-primary rounded-full font-label-caps text-[10px] font-bold leading-none"
                      data-cart-badge @if($cartCount === 0) hidden @endif>{{ $cartCount }}</span>
            </button>

            @auth
                <div class="relative" id="user-dropdown-container">
                    <button aria-label="Akun Pengguna"
                            class="flex items-center gap-2 p-1.5 rounded-full hover:bg-surface-container-low transition-colors"
                            id="user-dropdown-btn" type="button">
                        @if ($user->avatar)
                            <img alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover" src="{{ $user->avatarUrl() }}">
                        @else
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                            </div>
                        @endif
                        <span class="material-symbols-outlined text-outline text-[18px] hidden sm:block">expand_more</span>
                    </button>

                    <div class="hidden absolute right-0 top-12 w-60 bg-surface-container-lowest rounded-xl shadow-[0_8px_24px_-4px_rgba(15,23,42,0.08)] py-2 z-50"
                         id="user-dropdown-menu">
                        <div class="px-4 py-2 border-b border-surface-container-low">
                            <p class="font-label-lg text-label-lg text-on-surface font-semibold truncate">Halo, {{ Str::before($user->name, ' ') }}!</p>
                            <p class="font-body-sm text-body-sm text-outline">{{ ucfirst($user->role) }}</p>
                        </div>

                        <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                           href="{{ route('akun.index') }}">
                            <span class="material-symbols-outlined text-[18px]">account_circle</span>Dashboard Saya
                        </a>
                        <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                           href="{{ route('akun.pesanan') }}">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span>Riwayat Pesanan
                        </a>
                        <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                           href="{{ route('wishlist.index') }}">
                            <span class="material-symbols-outlined text-[18px]">favorite</span>Wishlist
                        </a>
                        <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors"
                           href="{{ route('akun.notifikasi') }}">
                            <span class="material-symbols-outlined text-[18px]">notifications</span>Notifikasi
                        </a>

                        @if ($user->isStaff())
                            <a class="flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-primary hover:bg-primary/10 transition-colors"
                               href="{{ route('admin.dashboard') }}">
                                <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>Panel Admin
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="border-t border-surface-container-low mt-1 pt-1">
                            @csrf
                            <button class="w-full flex items-center gap-2 px-4 py-2 font-body-sm text-body-sm text-secondary hover:bg-secondary-fixed/20 transition-colors" type="submit">
                                <span class="material-symbols-outlined text-[18px]">logout</span>Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a aria-label="Masuk"
                   class="px-2.5 sm:px-3 py-2 inline-flex items-center gap-1.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-semibold hover:bg-primary-container transition-colors"
                   href="{{ route('login') }}">
                    <span class="material-symbols-outlined text-[20px] sm:hidden" aria-hidden="true">person</span>
                    <span class="hidden sm:inline">Masuk</span>
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- Drawer mobile --}}
<div class="fixed inset-0 bg-inverse-surface/40 backdrop-blur-sm z-50 hidden transition-opacity opacity-0" id="mobile-drawer-backdrop"></div>
<aside class="fixed top-0 left-0 bottom-0 w-80 max-w-[85vw] bg-surface z-[60] -translate-x-full transition-transform duration-300 ease-in-out flex flex-col"
       id="mobile-drawer" aria-label="Menu navigasi">
    <div class="h-20 px-6 flex items-center justify-between bg-surface-container-low">
        <div class="flex items-center gap-2">
            <img alt="Logo" class="h-7 w-auto object-contain" src="{{ asset('images/logo.png') }}">
            <span class="font-headline-sm text-headline-sm text-primary font-bold">PETCARE</span>
        </div>
        <button class="p-2 text-on-surface-variant hover:text-on-surface rounded-xl" id="btn-mobile-menu-close" type="button" aria-label="Tutup menu">
            <span class="material-symbols-outlined text-[24px]">close</span>
        </button>
    </div>

    <div class="p-4">
        <form action="{{ route('produk.index') }}" method="GET" class="relative">
            <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[18px] pointer-events-none">search</span>
            <label class="sr-only" for="mobile-search-input">Cari produk</label>
            <input class="w-full h-10 pl-9 pr-9 rounded-xl bg-surface-container-low border border-surface-outline-app/80 font-body-sm text-body-sm text-on-surface placeholder:text-outline/90 focus:outline-none focus:bg-surface-container-lowest focus:border-primary/40 focus:ring-2 focus:ring-primary/15 transition-all"
                   id="mobile-search-input"
                   name="q" placeholder="Cari makanan, vitamin, aksesoris..." type="search" value="{{ request('q') }}" autocomplete="off"
                   role="combobox"
                   aria-autocomplete="list"
                   aria-expanded="false"
                   aria-controls="mobile-search-dropdown"
                   data-search-url="{{ route('produk.index') }}"
                   data-live-search="mobile-search-dropdown">
            <button class="hidden absolute right-2 top-[7px] p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors"
                    data-search-clear type="button" aria-label="Bersihkan pencarian">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
            <div class="hidden absolute left-0 right-0 top-12 bg-surface-container-lowest rounded-2xl border border-surface-outline-app shadow-[0_16px_40px_-12px_rgba(15,23,42,0.18)] p-1.5 z-50 max-h-[60vh] overflow-y-auto"
                 id="mobile-search-dropdown" role="listbox"></div>
        </form>
    </div>

    <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
        @foreach ($navItems as $item)
            @php $isActive = $active === $item['route']; @endphp
            <a @class([
                'flex items-center justify-between px-4 py-3 rounded-xl transition-colors',
                'bg-primary-container text-on-primary-container font-semibold' => $isActive,
                'font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' => ! $isActive,
            ]) href="{{ route($item['route']) }}">
                <span>{{ $item['label'] }}</span>
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
            </a>
        @endforeach
    </nav>

    <div class="p-6 bg-surface-container-low mt-auto">
        <button type="button"
                data-theme-toggle
                class="w-full flex items-center gap-2 px-4 py-3 mb-4 rounded-xl bg-surface-container-lowest text-on-surface font-label-lg text-label-lg hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[20px]" data-theme-toggle-icon>light_mode</span>
            <span>Ganti Mode Tampilan</span>
        </button>

        @auth
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[20px]">person</span>
                </div>
                <div class="min-w-0">
                    <p class="font-title-md text-title-md text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="font-body-sm text-body-sm text-outline">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
            <a class="w-full flex items-center justify-center py-2.5 bg-surface-container-lowest text-on-surface rounded-xl font-label-lg text-label-lg hover:bg-surface-variant transition-colors"
               href="{{ route('akun.index') }}">Dashboard Saya</a>
        @else
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[20px]">person</span>
                </div>
                <div>
                    <p class="font-title-md text-title-md text-on-surface">Akun Pelanggan</p>
                    <p class="font-body-sm text-body-sm text-outline">Masuk untuk poin belanja</p>
                </div>
            </div>
            <a class="w-full flex items-center justify-center py-2.5 bg-primary text-on-primary rounded-xl font-label-lg text-label-lg hover:bg-primary-container transition-colors"
               href="{{ route('login') }}">Masuk / Daftar</a>
        @endauth
    </div>
</aside>

@include('layouts.partials.cart-drawer')