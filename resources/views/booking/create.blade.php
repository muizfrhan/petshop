@extends('layouts.app')

@section('title', 'Booking Layanan')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="['Booking' => null]" />

            <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Booking Cepat</span>
            <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mt-1 mb-3">
                Jadwalkan Perawatan Sahabatmu
            </h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                Isi formulir di bawah ini, tim kami akan menghubungi Anda untuk konfirmasi jadwal.
            </p>
        </div>
    </section>

    <section class="w-full py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Form --}}
                <div class="lg:col-span-2">
                    <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="surface-card p-6">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">1</span>
                                Pilih Layanan
                            </h2>

                            @if ($services->isEmpty())
                                <p class="font-body-sm text-body-sm text-outline py-4 text-center">
                                    Belum ada layanan tersedia saat ini.
                                </p>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($services as $service)
                                        <label @class([
                                            'flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-colors',
                                            'border-primary bg-primary-fixed/20' => old('service_id', $selectedService?->id) == $service->id,
                                            'border-surface-outline-app hover:bg-surface-container-low' => old('service_id', $selectedService?->id) != $service->id,
                                        ])>
                                            <input type="radio" name="service_id" value="{{ $service->id }}"
                                                   class="w-5 h-5 accent-primary"
                                                   @checked(old('service_id', $selectedService?->id) == $service->id)
                                                   required>
                                            <span class="material-symbols-outlined text-[22px] text-primary">{{ $service->icon ?: 'spa' }}</span>
                                            <span class="min-w-0 flex-1">
                                                <span class="font-label-lg text-label-lg font-semibold text-on-surface block line-clamp-1">
                                                    {{ $service->name }}
                                                </span>
                                                <span class="font-label-md text-label-md text-outline">
                                                    Rp {{ number_format($service->price, 0, ',', '.') }} · {{ $service->durationLabel() }}
                                                </span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="surface-card p-6">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">2</span>
                                Data Pemesan
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-form-field name="customer_name" label="Nama Anda" required
                                              :value="$user?->name ?? old('customer_name')" />
                                <x-form-field name="phone" label="Nomor WhatsApp" required
                                              :value="$user?->phone ?? old('phone')" placeholder="08xxxxxxxxxx" />
                                <div class="sm:col-span-2">
                                    <x-form-field name="email" label="Email" type="email"
                                                  :value="$user?->email ?? old('email')" placeholder="Opsional" />
                                </div>
                            </div>
                        </div>

                        <div class="surface-card p-6">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">3</span>
                                Data Sahabat
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-form-field name="pet_name" label="Nama Hewan" required
                                              :value="old('pet_name')" placeholder="Contoh: Milo" />
                                <x-form-field name="pet_type" label="Jenis Hewan" type="select"
                                              :value="old('pet_type')" placeholder="Pilih jenis">
                                    @foreach (['anjing', 'kucing', 'burung', 'ikan', 'kelinci', 'lainnya'] as $type)
                                        <option value="{{ $type }}" @selected(old('pet_type') === $type)>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </x-form-field>
                                <x-form-field name="pet_breed" label="Ras"
                                              :value="old('pet_breed')" placeholder="Contoh: Persia" />
                                <x-form-field name="pet_weight" label="Berat (kg)" type="number" step="0.1"
                                              :value="old('pet_weight')" placeholder="Contoh: 4.5" />
                                <div class="sm:col-span-2">
                                    <x-form-field name="notes" label="Catatan Tambahan" type="textarea"
                                                  :value="old('notes')"
                                                  placeholder="Alergi, riwayat penyakit, atau permintaan khusus lainnya." />
                                </div>
                            </div>
                        </div>

                        <div class="surface-card p-6">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">4</span>
                                Jadwal
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-form-field name="booking_date" label="Tanggal" type="date" required
                                              :value="old('booking_date', now()->addDay()->format('Y-m-d'))"
                                              :min="now()->format('Y-m-d')"
                                              :max="now()->addMonths(3)->format('Y-m-d')" />

                                <div>
                                    <label class="form-label" for="booking_time">
                                        Jam <span class="text-error">*</span>
                                    </label>
                                    <select id="booking_time" name="booking_time" required
                                            class="form-input @error('booking_time') is-invalid @enderror">
                                        <option value="">-- Pilih jam --</option>
                                        @foreach (['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'] as $time)
                                            <option value="{{ $time }}" @selected(old('booking_time') === $time)>{{ $time }}</option>
                                        @endforeach
                                    </select>
                                    @error('booking_time')
                                        <p class="mt-1.5 font-body-sm text-body-sm text-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-primary text-on-primary font-headline-sm text-headline-sm font-bold hover:bg-primary-container transition-all shadow-md">
            Kirim Booking
                        </button>
                    </form>
                </div>

                {{-- Info --}}
                <aside class="lg:col-span-1">
                    <div class="surface-card p-6 lg:sticky lg:top-24">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Informasi Booking</h2>

                        <div class="space-y-4">
                            @foreach ([
                                ['event_available', 'Jadwal maksimal 3 bulan ke depan'],
                                ['notifications_active', 'Konfirmasi via WhatsApp maksimal 1x24 jam'],
                                ['payments', 'Pembayaran di tempat setelah layanan selesai'],
                                ['event_busy', 'Bisa dijadwal ulang kapan saja'],
                            ] as [$icon, $text])
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[18px] text-primary shrink-0 mt-px">{{ $icon }}</span>
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $text }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-6 border-t border-surface-container-low">
                            <p class="font-label-caps text-label-caps text-outline uppercase mb-2">Butuh bantuan?</p>
                            <a href="https://wa.me/{{ config('petshop.store.whatsapp') }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 font-body-sm text-body-sm text-primary font-semibold hover:underline">
                                <span class="material-symbols-outlined text-[16px]">chat</span>
                                Chat WhatsApp CS
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection