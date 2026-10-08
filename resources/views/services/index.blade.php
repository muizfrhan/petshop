@extends('layouts.app')

@section('title', 'Layanan Care')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="relative w-full -mt-20 pt-28 pb-14 overflow-hidden bg-gradient-to-b from-surface-container via-surface to-background">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 relative">
            <x-breadcrumb :items="['Layanan' => null]" />

            <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Perawatan Profesional</span>
            <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mt-1 mb-4">
                Layanan Care untuk Setiap Kebutuhan
            </h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
Dari grooming rutin hingga penanganan medis yang kompleks, semua ditangani tenaga profesional
            berlisensi dengan peralatan standar veteriner.
            </p>
        </div>
    </section>

    <section class="w-full py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            @if ($services->isEmpty())
                <x-empty-state
                    title="Belum ada layanan tersedia"
                    description="Layanan kami sedang disiapkan. Silakan cek kembali nanti."
                    icon="medical_services" />
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($services as $index => $service)
                        <x-service-card :service="$service" :index="$index" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="w-full py-12 bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ([
                    ['verified', 'Pet Handler Bersertifikat', 'Semua groomer dan handler mengikuti pelatihan berkala dan praktik rutin.'],
                    ['medical_services', 'Klinik Bersertifikat', 'Fasilitas klinik dengan peralatan standar untuk tindakan medis.'],
                    ['schedule', 'Booking Cepat', 'Konfirmasi jadwal via WhatsApp dalam hitungan menit.'],
                ] as [$icon, $title, $desc])
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-[24px]">{{ $icon }}</span>
                        </div>
                        <div>
                            <h3 class="font-title-md text-title-md text-on-surface font-bold mb-1">{{ $title }}</h3>
                            <p class="font-body-sm text-body-sm text-outline leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection