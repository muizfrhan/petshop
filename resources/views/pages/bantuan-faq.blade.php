@extends('layouts.app')

@section('title', 'Bantuan & FAQ')

@php
    $faqs = [
        [
            'Pesanan & Pengiriman',
            [
                ['Apakah produk yang dijual asli?', 'Ya. Seluruh produk bergaransi 100% original. Bila Anda menemukan produk yang tidak sesuai, kami siap mengganti atau mengembalikan dana Anda.'],
                ['Berapa lama proses pengiriman?', 'Reguler 2-4 hari kerja, Express 1-2 hari kerja, dan Same Day untuk pesanan sebelum pukul 11.00 WIB di area Jabodetabek.'],
                ['Apakah bisa kirim ke luar Jawa?', 'Bisa. Kami mengirim ke seluruh Indonesia. Untuk wilayah timur, waktu pengiriman bertambah 1-2 hari kerja.'],
                ['Bagaimana cara melacak pesanan?', 'Masuk ke akun Anda lalu buka menu Riwayat Pesanan. Nomor pesanan dan status pengiriman dapat dipantau kapan saja.'],
                ['Bagaimana cara membatalkan pesanan?', 'Buka detail pesanan lalu tekan Batalkan Pesanan. Pembatalan gratis selama pesanan belum dikirim.'],
            ],
        ],
        [
            'Pembayaran',
            [
                ['Metode pembayaran apa saja yang tersedia?', 'Transfer Bank, QRIS, E-Wallet, dan COD. Pembayaran transfer diverifikasi secara manual oleh tim kami.'],
                ['Berapa lama verifikasi pembayaran?', 'Paling lama 1x24 jam kerja setelah bukti pembayaran diunggah.'],
                ['Apakah harga sudah termasuk pajak?', 'Harga sudah termasuk pajak. Ongkos kirim dihitung terpisah saat checkout sesuai metode yang dipilih.'],
            ],
        ],
        [
            'Layanan & Booking',
            [
                ['Apakah layanan grooming bisa diantor?', 'Bisa. Pilih layanan Home Service Perawatan, groomer kami akan datang ke alamat Anda sesuai jadwal yang disepakati.'],
                ['Berapa lama masa berlaku voucher?', 'Voucher memiliki masa berlaku yang tercantum pada detail voucher. Silakan periksa tanggal berakhir sebelum digunakan.'],
                ['Bagaimana cara membatalkan booking?', 'Hubungi WhatsApp kami dengan menyertakan nomor booking. Tim kami akan membantu menjadwalkan ulang atau membatalkan.'],
            ],
        ],
        [
            'Akun & Keamanan',
            [
                ['Bagaimana cara mengganti password?', 'Masuk ke akun Anda, buka menu Akun Saya, pilih Edit Profil, lalu isi bagian Ubah Password.'],
                ['Saya lupa password, apa yang harus dilakukan?', 'Gunakan tautan Lupa password pada halaman masuk. Kami akan mengirimkan tautan reset ke email Anda.'],
                ['Apakah data saya aman?', 'Password disimpan dalam bentuk terenkripsi dan tidak dapat dibaca siapa pun, termasuk administrator kami.'],
            ],
        ],
    ];
@endphp

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <x-page-header
        title="Bantuan & FAQ"
        eyebrow="Dukungan Pelanggan"
        intro="Temukan jawaban atas pertanyaan yang sering diajukan. Jika belum menemukan yang Anda cari, hubungi tim kami melalui WhatsApp." />

    <section class="w-full py-12">
        <div class="max-w-4xl mx-auto px-6 space-y-10">
            @foreach ($faqs as [$groupTitle, $items])
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">{{ $groupTitle }}</h2>

                    <div class="space-y-3">
                        @foreach ($items as [$question, $answer])
                            <details class="surface-card p-5 group">
                                <summary class="flex items-center justify-between gap-4 cursor-pointer list-none">
                                    <span class="font-title-md text-title-md text-on-surface font-semibold">{{ $question }}</span>
                                    <span class="material-symbols-outlined text-[20px] text-outline transition-transform group-open:rotate-180 shrink-0">
                                        expand_more
                                    </span>
                                </summary>
                                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-3 pt-3 border-t border-surface-container-low">
                                    {{ $answer }}
                                </p>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <x-page-help />
        </div>
    </section>
</main>
@endsection