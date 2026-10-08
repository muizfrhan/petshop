@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="relative w-full -mt-20 pt-28 pb-16 lg:pb-24 overflow-hidden bg-gradient-to-b from-surface-container via-surface to-background">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-80 h-80 rounded-full bg-secondary-container/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <div class="lg:col-span-7 flex flex-col items-start z-10">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary-fixed/40 text-primary mb-6 shadow-sm">
                        <span class="material-symbols-outlined text-[18px] text-surface-tint">verified_user</span>
                        <span class="font-label-caps text-label-caps uppercase tracking-wider text-primary font-bold">
                            Semua Kebutuhan Hewan Kesayangan, Dalam Satu Tempat
                        </span>
                    </div>

                    <h1 class="font-display-lg text-display-lg-mobile sm:text-display-lg text-on-surface font-extrabold tracking-tight mb-6">
                        Rawat Sahabat Berbulumu dengan <span class="text-primary italic">Lebih Baik.</span>
                    </h1>

                    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mb-8 leading-relaxed">
                        Temukan makanan premium, perlengkapan bermain, suplemen kesehatan, dan layanan perawatan
                        medis tepercaya langsung di bawah pantauan dokter hewan berlisensi.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto mb-10">
                        <a href="{{ route('produk.index') }}"
                           class="w-full sm:w-auto px-8 py-4 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold shadow-md hover:bg-primary-container transition-all flex items-center justify-center gap-2 group">
                            <span>Belanja Sekarang</span>
                            <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                        <a href="{{ route('layanan.index') }}"
                           class="w-full sm:w-auto px-8 py-4 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-semibold hover:bg-surface-container-highest transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-secondary">spa</span>
                            <span>Lihat Layanan Care</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 w-full pt-6">
                        @foreach ([
                            ['verified', '100% Asli', 'Garansi Produk', 'bg-primary-fixed text-primary'],
                            ['groups', number_format($stats['products'] + 1000, 0, ',', '.').'+', 'Produk Terpilih', 'bg-secondary-fixed text-secondary'],
                            ['star', '4.9 / 5.0', 'Rating Klinik', 'bg-surface-variant text-surface-tint'],
                            ['stethoscope', 'Vet Resmi', 'Berlisensi Aktif', 'bg-tertiary-fixed text-tertiary-container'],
                        ] as [$icon, $value, $label, $color])
                            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/80 shadow-sm">
                                <div class="w-9 h-9 rounded-lg {{ $color }} flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface font-bold leading-tight">{{ $value }}</p>
                                    <p class="font-label-caps text-label-caps text-outline truncate">{{ $label }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div class="rounded-3xl overflow-hidden bg-surface-container-lowest shadow-xl aspect-[4/5] relative">
                            <img class="w-full h-full object-cover"
                                 src="{{ asset('images/hero-card.jpg') }}"
                                 alt="Hewan kesayangan di klinik PETCARE HOUSE"
                                 width="512" height="640">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent flex flex-col justify-end p-6 text-on-primary">
                                <span class="font-label-caps text-label-caps text-tertiary-fixed uppercase font-bold tracking-widest mb-1">Perawatan Terintegrasi</span>
                                <p class="font-headline-sm text-headline-sm font-bold">Kesejahteraan Hewan Adalah Prioritas Kami</p>
                            </div>
                        </div>

                        <div class="absolute -top-4 -left-6 sm:-left-8 bg-surface-container-lowest p-4 rounded-2xl shadow-xl flex items-center gap-3 backdrop-blur-md">
                            <div class="w-12 h-12 rounded-xl bg-surface-tint flex items-center justify-center text-on-primary shadow-sm">
                                <span class="material-symbols-outlined text-[24px]">medical_services</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <p class="font-label-md text-label-md font-bold text-on-surface">Klinik Siaga</p>
                                </div>
                                <p class="font-body-sm text-body-sm text-outline">Konsultasi Dokter Aktif</p>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -right-4 sm:-right-6 bg-secondary text-on-primary px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3">
                            <span class="material-symbols-outlined text-[26px]">local_fire_department</span>
                            <div>
                                <p class="font-label-caps text-label-caps font-bold tracking-wider opacity-90 uppercase">Voucher Perdana</p>
                                <p class="font-label-lg text-label-lg font-bold">Potongan Rp50.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('home.partials.categories', ['categories' => $categories])

    @include('home.partials.flash-sale')

    @include('home.partials.featured-products', ['products' => $featuredProducts])

    @include('home.partials.services', ['services' => $services])

    @include('home.partials.articles', ['articles' => $articles])

    @include('home.partials.testimonials', ['testimonials' => $testimonials])

    @include('home.partials.booking-cta')
    </main>
@endsection