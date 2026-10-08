@extends('layouts.admin')

@section('title', 'Kelola Pelanggan')

@section('admin-content')
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="lg:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari nama, email, atau nomor telepon..." class="form-input">
            </div>

            <select name="role" class="form-input">
                <option value="">Semua Peran</option>
                <option value="customer" @selected(($filters['role'] ?? '') === 'customer')>Pelanggan</option>
                <option value="staff" @selected(($filters['role'] ?? '') === 'staff')>Staff</option>
                <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Administrator</option>
            </select>

            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
            </select>
        </div>

        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (array_filter($filters))
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $users->total() }} pengguna</span>
        </div>
    </form>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-5">
        @foreach ([
            'all' => 'Semua', 'customer' => 'Pelanggan', 'admin' => 'Administrator', 'staff' => 'Staff', 'inactive' => 'Nonaktif',
        ] as $key => $label)
            <a href="{{ route('admin.users.index', array_filter(['q' => $filters['q'] ?? null, 'role' => $key === 'all' ? null : $key])) }}"
               @class([
                   'surface-card p-4 transition-colors',
                   'ring-2 ring-primary' => (($filters['role'] ?? '') === $key) || ($key === 'all' && empty($filters['role']) && empty($filters['status'])),
               ])>
                <p class="font-label-caps text-label-caps text-outline uppercase truncate">{{ $label }}</p>
                <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $counts[$key] ?? 0 }}</p>
            </a>
        @endforeach
    </div>

    <div class="surface-card overflow-hidden">
        @if ($users->isEmpty())
            <x-empty-state title="Pengguna tidak ditemukan" description="Coba ubah filter pencarian Anda." icon="group"
                           :action="route('admin.users.index')" action-label="Reset Filter" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Kontak</th>
                            <th>Pesanan</th>
                            <th>Peran</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if ($user->avatar)
                                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                                                 class="w-10 h-10 rounded-full object-cover shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">
                                                {{ $user->name }}
                                            </p>
                                            <p class="font-label-md text-label-md text-outline">
                                                Daftar {{ $user->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $user->email }}</p>
                                    @if ($user->phone)
                                        <p class="font-label-md text-label-md text-outline">{{ $user->phone }}</p>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $user->orders_count }}</span>
                                </td>
                                <td>
                                    @php
                                        $roleMap = [
                                            'admin' => ['bg-primary-container text-on-primary-container', 'Admin'],
                                            'staff' => ['bg-tertiary-fixed text-tertiary-container', 'Staff'],
                                            'default' => ['bg-surface-container text-on-surface-variant', 'Pelanggan'],
                                        ];
                                        [$classes, $label] = $roleMap[$user->role] ?? $roleMap['default'];
                                    @endphp
                                    <span class="badge {{ $classes }}">{{ $label }}</span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $user->isActive(),
                                        'bg-error-container text-on-error-container' => ! $user->isActive(),
                                    ])>{{ $user->isActive() ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors"
                                           title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>

                                        <form action="{{ route('admin.users.status', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-2 rounded-lg hover:bg-surface-container-low transition-colors {{ $user->isActive() ? 'text-error' : 'text-primary' }}"
                                                    title="{{ $user->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <span class="material-symbols-outlined text-[18px]">{{ $user->isActive() ? 'person_off' : 'how_to_reg' }}</span>
                                            </button>
                                        </form>

                                        @if (! $user->isAdmin())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 rounded-lg text-error hover:bg-error-container transition-colors"
                                                        title="Hapus">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$users" />
                </div>
            @endif
        @endif
    </div>
@endsection