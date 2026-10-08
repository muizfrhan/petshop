@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
        <x-page-header
            title="Mengenal PETCARE HOUSE"
            eyebrow="Tentang Kami"
            icon="pets"
            intro="Kami percaya setiap hewan kesayangan pantas mendapat perawatan terbaik. Itulah alasan kami hadir." />

        <section class="py-14 lg:py-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div>
                    <h2 class="font-headline-xl text-headline-xl text-on-surface font-extrabold tracking-tight mb-5">
                        Sahabat Berbulu Mulai dari Perawatan yang Tepat
                    </h2>
                    <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-4">
                        PETCARE HOUSE berdiri pada tahun 2014 sebagai toko kecil di Bandung yang hanya menjual makanan hewan.
                        Seiring meningkatnya permintaan, kami berkembang menjadi pusat perawatan terpadu: kebutuhan harian,
                        layanan grooming, hingga pemeriksaan medis oleh dokter hewan berlisensi.
                    </p>
                    <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                        Setiap produk yang kami jual melewati pemeriksaan kualitas, dan setiap hewan yang kami tangani
                        diperlakukan dengan perhatian yang sama seperti milik keluarga sendiri.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ([
                        ['2014', 'Tahun berdiri', 'primary'],
                        ['12+', 'Tahun pengalaman', 'secondary'],
                        ['40K+', 'Hewan terlayani', 'tertiary'],
                        ['15', 'Dokter hewan', 'variant'],
                    ] as [$value, $label, $color])
                        @php
                            $palette = match ($color) {
                                'secondary' => 'bg-secondary-fixed text-secondary',
                                'tertiary' => 'bg-tertiary-fixed text-tertiary-container',
                                'variant' => 'bg-surface-variant text-surface-tint',
                                default => 'bg-primary-fixed text-primary',
                            };
                        @endphp
                        <div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl {{ $palette }} mb-4">
                                <span class="material-symbols-outlined text-[22px]">emoji_nature</span>
                            </span>
                            <p class="font-display-lg text-display-lg-mobile text-on-surface font-extrabold">{{ $value }}</p>
                            <p class="font-label-caps text-label-caps text-outline uppercase tracking-wider">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 lg:py-20 bg-surface-container-low">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="font-headline-xl text-headline-xl text-on-surface font-extrabold tracking-tight mb-4">
                        Enam Pilar Layanan Kami
                    </h2>
                    <p class="font-body-lg text-body-lg text-on-surface-variant">
                        Mulai dari memilih produk sampai layanan selesai, kami mendampingi setiap langkah.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ([
                        ['verified', 'Produk 100% Asli', 'Setiap produk bergaransi resmi dan berasal dari distributor resmi. Barang palsu tidak pernah masuk katalog kami.', 'bg-primary-fixed text-primary'],
                        ['medical_services', 'Dokter Hewan Berlisensi', 'Pemeriksaan dilakukan oleh dokter dengan izin praktik aktif dan pengalaman lebih dari delapan tahun.', 'bg-secondary-fixed text-secondary'],
                        ['local_shipping', 'Pengiriman Cepat', 'Pengiriman di hari yang sama untuk area Kota Bandung bila pesanan masuk sebelum pukul 15.00 WIB.', 'bg-tertiary-fixed text-tertiary-container'],
                        ['health_and_safety', 'Hygiene Standar Klinik', 'Alat grooming disanitasi setelah tiap hewan, ruang tindakan steril, dan staff kami selalu memakai sarung tangan sekali pakai.', 'bg-surface-variant text-surface-tint'],
                        ['support_agent', 'Konsultasi Gratis', 'Tim kami siap membantu memilih produk yang tepat lewat WhatsApp tanpa biaya tambahan.', 'bg-primary-fixed text-primary'],
                        ['event_repeat', 'Layanan Berulang', 'Pelanggan dapat menjadwalkan perawatan rutin agar hewan tetap sehat dan berbulu mengkilap.', 'bg-secondary-fixed text-secondary'],
                    ] as [$icon, $title, $body, $palette])
                        <div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col h-full">
                            <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl {{ $palette }} mb-4">
                                <span class="material-symbols-outlined text-[26px]">{{ $icon }}</span>
                            </span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-2">{{ $title }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $body }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 lg:py-20">
            <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-secondary text-on-secondary mb-6">
                    <span class="material-symbols-outlined text-[32px]">forum</span>
                </span>
                <h2 class="font-headline-xl text-headline-xl text-on-surface font-extrabold tracking-tight mb-4">
                    Masih punya pertanyaan?
                </h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant mb-8">
                    Tim kami siap membantu setiap hari, pukul 08.00 sampai 20.00 WIB.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('kontak') }}"
                       class="px-8 py-4 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                        Hubungi Kami
                    </a>
                    <a href="{{ route('produk.index') }}"
                       class="px-8 py-4 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-semibold hover:bg-surface-container-highest transition-colors">
                        Lihat Katalog
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection