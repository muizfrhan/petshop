@extends('layouts.app')

@section('title', 'Syarat & Ketentuan')

@php
    $sections = [
        [
            'Definisi',
            'Istilah "Kami" mengacu pada ' . config('petshop.store.name') . '. Istilah "Pengguna" mencakup pembeli, pelanggan, dan pengunjung situs. Istilah "Produk" mencakup barang dagangan dan layanan yang kami tawarkan.',
        ],
        [
            'Akun Pengguna',
            'Anda wajib mendaftar menggunakan informasi yang benar dan lengkap. Anda bertanggung jawab menjaga kerahasiaan kata sandi dan seluruh aktivitas pada akun Anda. Kami tidak bertanggung jawab atas kerugian akibat penyalahgunaan akun oleh pihak ketiga.',
        ],
        [
            'Pemesanan',
            'Pesanan dianggap sah setelah konfirmasi pembayaran diterima atau bukti transfer terverifikasi. Kami berhak membatalkan pesanan jika stok tidak mencukupi atau ditemukan kekeliruan harga pada sistem.',
        ],
        [
            'Harga dan Pembayaran',
            'Semua harga ditampilkan dalam Rupiah dan belum termasuk ongkos kirim yang dihitung saat checkout. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan, namun harga yang berlaku adalah harga pada saat pesanan dibuat.',
        ],
        [
            'Pengiriman',
            'Estimasi waktu pengiriman bergantung pada metode yang dipilih dan kurir yang tersedia. Pesanan yang sudah dikirim tidak dapat dibatalkan. Pesanan yang tidak diterima setelah tiga hari akan otomatis diproses pengembaliannya.',
        ],
        [
            'Retur dan Penukaran',
            'Retur hanya dapat dilakukan untuk produk cacat produksi dengan bukti foto dan nomor pesanan. Batas waktu pengajuan retur adalah tiga hari setelah produk diterima.',
        ],
        [
            'Layanan Perawatan',
            'Pemesanan layanan grooming dan perawatan memerlukan data hewan yang akurat. Kami berhak menolak bila pemeriksaan awal menemukan kondisi yang necessitates penanganan oleh dokter hewan.',
        ],
        [
            'Ulasan Pelanggan',
            'Ulasan hanya dapat diberikan oleh pengguna yang telah melakukan pembelian dan menyelesaikan pesanan. Ulasan yang melanggar norma akan dihapus tanpa pemberitahuan.',
        ],
        [
            'Hak Kekayaan Intelektual',
            'Seluruh konten pada situs ini, termasuk teks, gambar, dan logo, adalah milik Kami dan dilindungi hukum hak cipta.',
        ],
        [
            'Perubahan Ketentuan',
            'Kami berhak memperbarui ketentuan ini kapan saja. Perubahan berlaku segera setelah dipublikasikan di halaman ini.',
        ],
        [
            'Hukum yang Berlaku',
            'Ketentuan ini tunduk pada hukum yang berlaku di wilayah Republik Indonesia.',
        ],
    ];
@endphp

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <x-page-header
        title="Syarat & Ketentuan"
        eyebrow="Dokumen Legal"
        intro="Ketentuan ini mengatur penggunaan situs dan layanan {{ config('petshop.store.name') }}. Dengan menggunakan layanan kami, Anda menyatakan telah membaca dan menyetujui seluruh isi di bawah ini." />

    <section class="w-full py-12">
        <div class="max-w-4xl mx-auto px-6">
            <div class="surface-card p-6 lg:p-8">
                <ol class="space-y-8">
                    @foreach ($sections as $i => [$heading, $body])
                        <li class="flex items-start gap-4">
                            <span class="w-8 h-8 rounded-xl bg-primary-fixed text-primary flex items-center justify-center font-label-md text-label-md font-bold shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <h2 class="font-title-md text-title-md text-on-surface font-bold mb-1.5">{{ $heading }}</h2>
                                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $body }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <p class="font-body-sm text-body-sm text-outline mt-10 pt-6 border-t border-surface-container-low">
                    Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <x-page-help />
        </div>
    </section>
</main>
@endsection