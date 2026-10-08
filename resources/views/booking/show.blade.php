@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10">
        <div class="max-w-4xl mx-auto px-6">
            <x-breadcrumb :items="[
                'Layanan' => route('layanan.index'),
                'Booking' => null,
            ]" />

            {{-- Konfirmasi sukses --}}
            <div class="surface-card p-8 text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-tertiary-fixed flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-[40px] text-on-tertiary-fixed">check_circle</span>
                </div>

                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mb-2">
                    Booking Berhasil Dikirim
                </h1>
                <p class="font-body-md text-body-md text-outline max-w-lg mx-auto leading-relaxed">
                    Tim kami akan menghubungi Anda melalui nomor WhatsApp
                    <span class="font-semibold text-on-surface">{{ $booking->phone }}</span>
                    untuk konfirmasi jadwal.
                </p>

                <p class="font-label-caps text-label-caps text-outline uppercase mt-6 mb-1">Nomor Booking</p>
                <p class="font-headline-sm text-headline-sm text-primary font-bold">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            {{-- Detail --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="surface-card p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Informasi Booking</h2>

                    <dl class="space-y-3">
                        @foreach ([
                            'Status' => $booking->statusLabel(),
                            'Layanan' => $booking->service?->name ?? 'Layanan dihapus',
                            'Tanggal' => $booking->booking_date->format('d F Y'),
                            'Jam' => $booking->booking_time,
                            'Estimasi Biaya' => 'Rp '.number_format($booking->price, 0, ',', '.'),
                            'Dibuat' => $booking->created_at->format('d/m/Y H:i'),
                        ] as $label => $value)
                            <div class="flex justify-between gap-4 py-1.5 border-b border-surface-container-low last:border-0">
                                <dt class="font-body-sm text-body-sm text-outline shrink-0">{{ $label }}</dt>
                                <dd class="font-body-sm text-body-sm text-on-surface font-semibold text-right">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="surface-card p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Data Sahabat</h2>

                    <dl class="space-y-3">
                        @foreach ([
                            'Nama Hewan' => $booking->pet_name,
                            'Jenis' => $booking->pet_type ? ucfirst($booking->pet_type) : '-',
                            'Ras' => $booking->pet_breed ?: '-',
                            'Berat' => $booking->pet_weight ? $booking->pet_weight.' kg' : '-',
                            'Pemesan' => $booking->customer_name,
                            'Kontak' => $booking->phone,
                        ] as $label => $value)
                            <div class="flex justify-between gap-4 py-1.5 border-b border-surface-container-low last:border-0">
                                <dt class="font-body-sm text-body-sm text-outline shrink-0">{{ $label }}</dt>
                                <dd class="font-body-sm text-body-sm text-on-surface font-semibold text-right">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    @if ($booking->notes)
                        <div class="mt-4 p-3 rounded-xl bg-surface-container-low">
                            <p class="font-label-md text-label-md font-bold text-on-surface mb-1">Catatan Anda</p>
                            <p class="font-body-sm text-body-sm text-outline">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($booking->admin_note)
                <div class="surface-card p-6 mb-8">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-2">Catatan dari Tim Kami</h2>
                    <p class="font-body-sm text-body-sm text-outline leading-relaxed">{{ $booking->admin_note }}</p>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('layanan.index') }}"
                   class="px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all inline-flex items-center justify-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">medical_services</span>
                    Lihat Layanan Lainnya
                </a>

                <a href="https://wa.me/{{ config('petshop.store.whatsapp') }}?text={{ rawurlencode('Halo, saya ingin menanyakan status booking #' . str_pad($booking->id, 6, '0', STR_PAD_LEFT)) }}"
                   target="_blank" rel="noopener"
                   class="px-7 py-3.5 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-highest transition-all inline-flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    Tanya via WhatsApp
                </a>
            </div>
        </div>
    </section>
</main>
@endsection