@extends('layouts.admin')

@section('title', 'Kelola Booking')

@section('admin-content')
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="lg:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari pemesan, nama hewan, atau telepon..." class="form-input">
            </div>

            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>
                        {{ $label }} ({{ $counts[$key] ?? 0 }})
                    </option>
                @endforeach
            </select>

            <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="form-input" aria-label="Filter tanggal">
        </div>

        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (array_filter($filters))
                <a href="{{ route('admin.bookings.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $bookings->total() }} booking</span>
        </div>
    </form>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-5">
        @foreach ($statuses as $key => $label)
            <a href="{{ route('admin.bookings.index', ['status' => $key]) }}"
               @class([
                   'surface-card p-4 transition-colors',
                   'ring-2 ring-primary' => ($filters['status'] ?? '') === $key,
               ])>
                <p class="font-label-caps text-label-caps text-outline uppercase truncate">{{ $label }}</p>
                <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $counts[$key] ?? 0 }}</p>
            </a>
        @endforeach
    </div>

    <div class="surface-card overflow-hidden">
        @if ($bookings->isEmpty())
            <x-empty-state title="Booking tidak ditemukan" description="Coba ubah filter pencarian Anda." icon="calendar_month"
                           :action="route('admin.bookings.index')" action-label="Reset Filter" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Pemesan</th>
                            <th>Hewan</th>
                            <th>Layanan</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>
                                    <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $booking->customer_name }}</p>
                                    <p class="font-label-md text-label-md text-outline">{{ $booking->phone }}</p>
                                </td>
                                <td>
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $booking->pet_name }}</p>
                                    <p class="font-label-md text-label-md text-outline">
                                        {{ $booking->pet_type ? ucfirst($booking->pet_type) : '-' }}
                                        @if ($booking->pet_weight) · {{ $booking->pet_weight }} kg @endif
                                    </p>
                                </td>
                                <td>
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $booking->service?->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $booking->booking_date->format('d/m/Y') }}</p>
                                    <p class="font-label-md text-label-md text-outline">{{ $booking->booking_time }}</p>
                                </td>
                                <td><x-booking-status-badge :status="$booking->status" /></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($bookings->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$bookings" />
                </div>
            @endif
        @endif
    </div>
@endsection