@php
    $user = auth()->user();

    $items = [
        ['label' => 'Ringkasan', 'route' => 'akun.index', 'icon' => 'space_dashboard'],
        ['label' => 'Profil Saya', 'route' => 'akun.edit', 'icon' => 'account_circle'],
        ['label' => 'Pesanan', 'route' => 'akun.pesanan', 'icon' => 'receipt_long'],
        ['label' => 'Wishlist', 'route' => 'wishlist.index', 'icon' => 'favorite'],
        ['label' => 'Notifikasi', 'route' => 'akun.notifikasi', 'icon' => 'notifications', 'badge' => optional($user)->unreadNotifications()->count()],
    ];
@endphp

<div>
    {{-- Kartu profil (desktop) --}}
    <div class="hidden lg:flex surface-card p-4 items-center gap-3 mb-4">
        @if ($user?->avatar)
            <img alt="{{ $user->name }}" class="w-11 h-11 rounded-full object-cover shrink-0" src="{{ $user->avatarUrl() }}">
        @else
            <span class="w-11 h-11 rounded-full bg-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-on-primary text-[22px]">person</span>
            </span>
        @endif
        <div class="min-w-0">
            <p class="font-title-md text-title-md text-on-surface font-semibold truncate">{{ $user?->name }}</p>
            <p class="font-body-sm text-body-sm text-outline truncate">{{ $user?->email }}</p>
        </div>
    </div>

    <nav aria-label="Menu akun"
         class="flex gap-2 overflow-x-auto lg:flex-col lg:gap-1 lg:overflow-visible pb-1 lg:pb-0 -mx-1 px-1 lg:mx-0 lg:px-0"
         style="scrollbar-width: thin;">
        @foreach ($items as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <a @class([
                'flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-label-lg text-label-lg transition-all shrink-0 whitespace-nowrap',
                'bg-primary-container text-on-primary-container font-semibold' => $isActive,
                'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' => ! $isActive,
            ])
               @if ($isActive) aria-current="page" @endif
               href="{{ route($item['route']) }}">
                <span class="material-symbols-outlined text-[20px] shrink-0">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
                @if (! empty($item['badge']))
                    <span class="shrink-0 min-w-[18px] px-1.5 py-0.5 rounded-full bg-secondary text-on-secondary font-label-caps text-[10px] font-bold text-center leading-tight">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </a>
        @endforeach

        @if ($user?->isStaff())
            <a @class([
                'flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-label-lg text-label-lg transition-all shrink-0 whitespace-nowrap',
                'bg-primary text-on-primary font-semibold' => request()->routeIs('admin.*'),
                'text-primary hover:bg-primary/10' => ! request()->routeIs('admin.*'),
            ])
               href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined text-[20px] shrink-0">admin_panel_settings</span>
                <span>Panel Admin</span>
            </a>
        @endif
    </nav>

    {{-- Keluar (desktop) --}}
    <form action="{{ route('logout') }}" method="POST" class="hidden lg:block mt-3 pt-3 border-t border-surface-container-low">
        @csrf
        <button type="submit"
                class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl font-label-lg text-label-lg text-on-surface-variant hover:text-secondary hover:bg-secondary-fixed/20 transition-all">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span>Keluar</span>
        </button>
    </form>
</div>
