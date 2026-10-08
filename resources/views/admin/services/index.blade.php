@extends('layouts.admin')

@section('title', 'Kelola Layanan')

@section('admin-content')
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama layanan..." class="form-input">
            </div>
            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.services.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $services->total() }} layanan</span>
        </div>
    </form>

    <div class="surface-card overflow-hidden">
        @if ($services->isEmpty())
            <x-empty-state title="Layanan tidak ditemukan" description="Mulai dengan menambahkan layanan pertama Anda." icon="medical_services"
                           :action="route('admin.services.create')" action-label="Tambah Layanan" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Durasi</th>
                            <th>Booking</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if ($service->icon)
                                            <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                                                <span class="material-symbols-outlined text-[20px]">{{ $service->icon }}</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">
                                                {{ $service->name }}
                                            </p>
                                            <p class="font-mono font-label-md text-label-md text-outline">{{ $service->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-primary">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">{{ $service->durationLabel() }}</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $service->bookings_count }}</span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $service->status === 'active',
                                        'bg-surface-container text-outline' => $service->status !== 'active',
                                    ])>{{ $service->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                              onsubmit="return confirm('Hapus layanan {{ $service->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($services->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$services" />
                </div>
            @endif
        @endif
    </div>
@endsection