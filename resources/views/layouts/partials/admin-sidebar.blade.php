@php
    $user = auth()->user();
    $isAdmin = $user?->isAdmin() ?? false;

    // Menu per role: item dengan 'admin' => true hanya untuk Administrator.
    $groups = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'space_dashboard', 'key' => 'dashboard'],
            ],
        ],
        [
            'label' => 'Katalog',
            'items' => [
                ['label' => 'Produk', 'route' => 'admin.products.index', 'icon' => 'inventory_2', 'key' => 'products'],
                ['label' => 'Kategori', 'route' => 'admin.categories.index', 'icon' => 'category', 'key' => 'categories', 'admin' => true],
                ['label' => 'Brand', 'route' => 'admin.brands.index', 'icon' => 'sell', 'key' => 'brands', 'admin' => true],
            ],
        ],
        [
            'label' => 'Transaksi',
            'items' => [
                ['label' => 'Pesanan', 'route' => 'admin.orders.index', 'icon' => 'receipt_long', 'key' => 'orders'],
                ['label' => 'Pembayaran', 'route' => 'admin.payments.index', 'icon' => 'payments', 'key' => 'payments', 'admin' => true],
                ['label' => 'Kupon', 'route' => 'admin.coupons.index', 'icon' => 'redeem', 'key' => 'coupons', 'admin' => true],
            ],
        ],
        [
            'label' => 'Layanan & Konten',
            'items' => [
                ['label' => 'Layanan', 'route' => 'admin.services.index', 'icon' => 'medical_services', 'key' => 'services'],
                ['label' => 'Booking', 'route' => 'admin.bookings.index', 'icon' => 'calendar_month', 'key' => 'bookings'],
                ['label' => 'Artikel', 'route' => 'admin.articles.index', 'icon' => 'article', 'key' => 'articles', 'admin' => true],
                ['label' => 'Ulasan', 'route' => 'admin.reviews.index', 'icon' => 'reviews', 'key' => 'reviews'],
            ],
        ],
        [
            'label' => 'Lainnya',
            'items' => [
                ['label' => 'Pelanggan', 'route' => 'admin.users.index', 'icon' => 'group', 'key' => 'users', 'admin' => true],
                ['label' => 'Laporan', 'route' => 'admin.reports.index', 'icon' => 'insights', 'key' => 'reports', 'admin' => true],
            ],
        ],
    ];

    $groups = array_values(array_filter(array_map(function ($group) use ($isAdmin) {
        $group['items'] = array_values(array_filter($group['items'], fn ($item) => ! ($item['admin'] ?? false) || $isAdmin));

        return $group['items'] ? $group : null;
    }, $groups)));

    $pendingCount = \App\Models\Order::whereIn('status', ['pending', 'paid', 'processing'])->count();
@endphp

<aside id="admin-sidebar"
       aria-label="Menu panel admin"
       class="fixed inset-y-0 left-0 z-50 w-[17.5rem] max-w-[85vw] bg-surface-container-lowest border-r border-surface-outline-app flex flex-col -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0">

    {{-- Brand --}}
    <div class="h-16 px-4 flex items-center gap-2 border-b border-surface-container-low shrink-0">
        <img alt="{{ config('petshop.store.name') }} Logo"
             class="h-7 w-auto object-contain shrink-0"
             src="{{ asset('images/logo.png') }}">
        <span class="shrink-0 px-2 py-0.5 rounded-full bg-primary-container text-on-primary-container font-label-caps text-label-caps uppercase">
            Admin
        </span>
        <button type="button"
                id="admin-sidebar-close"
                aria-label="Tutup menu navigasi"
                class="lg:hidden p-2 ml-auto -mr-1 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined text-[22px]">close</span>
        </button>
    </div>

    {{-- Filter menu --}}
    <div class="p-3 shrink-0">
        <label class="sr-only" for="admin-menu-filter">Cari menu</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">search</span>
            <input id="admin-menu-filter"
                   data-menu-filter
                   type="search"
                   autocomplete="off"
                   placeholder="Cari menu..."
                   class="w-full h-10 pl-9 pr-3 rounded-xl bg-surface-container-low border border-surface-outline-app/80 font-body-sm text-body-sm text-on-surface placeholder:text-outline/90 focus:outline-none focus:border-primary/40 focus:ring-2 focus:ring-primary/15 transition-all">
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 min-h-0 overflow-y-auto px-3 pb-3" data-admin-menu>
        @foreach ($groups as $group)
            <div data-menu-group>
                @if ($group['label'])
                    <p data-menu-label
                       class="hidden lg:block font-label-caps text-label-caps text-outline uppercase px-3 pt-4 pb-1.5">
                        {{ $group['label'] }}
                    </p>
                @endif

                <div class="space-y-1">
                    @foreach ($group['items'] as $item)
                        @php
                            $wildcard = str_ends_with($item['route'], '.index')
                                ? substr($item['route'], 0, -6) . '.*'
                                : null;

                            $isActive = request()->routeIs($item['route'])
                                || ($wildcard && request()->routeIs($wildcard));
                        @endphp

                        <a data-menu-item
                           @class([
                               'flex items-center gap-3 px-3 py-2.5 rounded-xl font-label-lg text-label-lg transition-all',
                               'bg-primary-container text-on-primary-container font-semibold shadow-sm' => $isActive,
                               'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' => ! $isActive,
                           ])
                           @if ($isActive) aria-current="page" @endif
                           href="{{ route($item['route']) }}">
                            <span class="material-symbols-outlined text-[20px] shrink-0">{{ $item['icon'] }}</span>
                            <span class="flex-1 truncate">{{ $item['label'] }}</span>

                            @if (($item['key'] ?? null) === 'orders' && $pendingCount)
                                <span class="shrink-0 min-w-[20px] px-1.5 py-0.5 rounded-full bg-secondary text-on-secondary font-label-caps text-[10px] font-bold text-center leading-tight">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    {{-- Profil singkat + aksi --}}
    <div class="border-t border-surface-container-low p-3 shrink-0">
        <div class="flex items-center gap-3 px-2 py-2 mb-1">
            @if ($user?->avatar)
                <img alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover shrink-0" src="{{ $user->avatarUrl() }}">
            @else
                <span class="w-9 h-9 rounded-full bg-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-primary text-[20px]">person</span>
                </span>
            @endif
            <div class="min-w-0">
                <p class="font-label-lg text-label-lg text-on-surface font-semibold truncate">{{ $user?->name }}</p>
                <p class="font-body-sm text-body-sm text-outline truncate">
                    {{ $isAdmin ? 'Administrator' : 'Staff' }}
                </p>
            </div>
        </div>

        <a href="{{ route('beranda') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-all">
            <span class="material-symbols-outlined text-[20px]">storefront</span>
            <span>Lihat Toko</span>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl font-label-lg text-label-lg text-secondary hover:bg-secondary-fixed/20 transition-all">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
