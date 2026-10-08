@extends('layouts.admin')

@section('title', 'Booking #' . str_pad($booking->id, 6, '0', STR_PAD_LEFT))
@section('subtitle', $booking->service?->name . ' · ' . $booking->pet_name)
@section('hideErrors', true)

@section('admin-content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6 pb-6 border-b border-surface-container-low">
                    <div>
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Status</p>
                        <x-booking-status-badge :status="$booking->status" />
                    </div>
                    <div class="text-right">
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Jadwal</p>
                        <p class="font-title-md text-title-md text-on-surface font-bold">
                            {{ $booking->booking_date->format('d F Y') }}
                        </p>
                        <p class="font-body-sm text-body-sm text-outline">{{ $booking->booking_time }}</p>
                    </div>
                </div>

                {{-- Ubah status --}}
                <form action="{{ route('admin.bookings.status', $booking) }}" method="POST" class="space-y-4 mb-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="form-label" for="status">Ubah Status</label>
                        <select id="status" name="status" class="form-input" required>
                            <option value="confirmed" @selected($booking->status === 'confirmed')>Konfirmasi</option>
                            <option value="completed" @selected($booking->status === 'completed')>Selesai</option>
                            <option value="cancelled" @selected($booking->status === 'cancelled')>Batalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="admin_note">Catatan Admin</label>
                        <textarea id="admin_note" name="admin_note" rows="3" class="form-input py-3"
                                  placeholder="Contoh: Mohon Confirmation ulang jadwalvia WhatsApp.">{{ old('admin_note', $booking->admin_note) }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Perbarui Status
                    </button>
                </form>

                {{-- Jadwal ulang --}}
                <div class="pt-6 border-t border-surface-container-low">
                    <h3 class="font-title-md text-title-md text-on-surface font-bold mb-4">Jadwalkan Ulang</h3>

                    <form action="{{ route('admin.bookings.reschedule', $booking) }}" method="POST"
                          class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="form-label" for="new_date">Tanggal Baru</label>
                            <input type="date" id="new_date" name="booking_date" class="form-input"
                                   value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label class="form-label" for="new_time">Jam Baru</label>
                            <input type="time" id="new_time" name="booking_time" class="form-input"
                                   value="{{ old('booking_time', $booking->booking_time) }}" required>
                        </div>

                        <button type="submit" class="py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors">
                            Jadwalkan Ulang
                        </button>
                    </form>
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Detail Hewan</h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([
                        'Nama Hewan' => $booking->pet_name,
                        'Jenis' => $booking->pet_type ? ucfirst($booking->pet_type) : '-',
                        'Ras' => $booking->pet_breed ?: '-',
                        'Berat' => $booking->pet_weight ? $booking->pet_weight.' kg' : '-',
                    ] as $label => $value)
                        <div>
                            <dt class="font-label-caps text-label-caps text-outline uppercase">{{ $label }}</dt>
                            <dd class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if ($booking->notes)
                    <div class="mt-5 p-4 rounded-xl bg-surface-container-low">
                        <p class="font-label-md text-label-md font-bold text-on-surface mb-1">Catatan Pelanggan</p>
                        <p class="font-body-sm text-body-sm text-outline leading-relaxed">{{ $booking->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Pemesan</h2>

                <dl class="space-y-3">
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Nama</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $booking->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Telepon</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $booking->phone) }}" target="_blank" rel="noopener"
                               class="text-primary hover:underline">{{ $booking->phone }}</a>
                        </dd>
                    </div>
                    @if ($booking->email)
                        <div>
                            <dt class="font-label-caps text-label-caps text-outline uppercase">Email</dt>
                            <dd class="font-body-sm text-body-sm text-on-surface">{{ $booking->email }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Akun</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">
                            {{ $booking->user ? $booking->user->name : 'Booking tamu' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Layanan</h2>
                <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $booking->service?->name ?? 'Layanan dihapus' }}</p>
                <p class="font-headline-sm text-headline-sm text-primary font-bold mt-1">
                    Rp {{ number_format($booking->price, 0, ',', '.') }}
                </p>
                <p class="font-label-caps text-label-caps text-outline uppercase mt-3">Dibuat</p>
                <p class="font-body-sm text-body-sm text-outline">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <a href="{{ route('admin.bookings.index') }}"
               class="w-full py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                Kembali ke Daftar Booking
            </a>
        </aside>
    </div>
@endsection