@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@php
    $sections = [
        [
            'Data yang Kami Kumpulkan',
            'Kami mengumpulkan data yang Anda berikan saat pendaftaran, checkout, dan booking, meliputi nama, email, nomor telepon, alamat pengiriman, serta riwayat pesanan dan ulasan Anda.',
        ],
        [
            'Penggunaan Data',
            'Data digunakan untuk memproses pesanan, mengatur layanan, mengirim notifikasi pesanan, memberikan dukungan pelanggan, serta meningkatkan kualitas layanan kami.',
        ],
        [
            'Penyimpanan dan Keamanan',
            'Data disimpan pada server dengan akses terbatas. Password disimpan dalam bentuk terenkripsi dan tidak dapat dibaca oleh siapa pun, termasuk administrator.',
        ],
        [
            'Berbagi Data',
            'Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Data hanya dibagikan kepada mitra logistik dan pembayaran seperlunya untuk menyelesaikan pesanan.',
        ],
        [
            'Hak Pengguna',
            'Anda berhak melihat, memperbarui, atau menghapus data pribadi Anda. Penghapusan akun dapat dilakukan melalui halaman pengaturan akun.',
        ],
        [
            'Cookie',
            'Situs ini menggunakan cookie untuk menjaga sesi login dan mengingat preferensi tampilan Anda. Cookie bersifat esensial agar fitur keranjang dan login berfungsi.',
        ],
        [
            'Perubahan Kebijakan',
            'Kebijakan ini dapat diperbarui sewaktu-waktu. Perubahan akan dipublikasikan di halaman ini beserta tanggal pembaruannya.',
        ],
    ];
@endphp

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <x-page-header
        title="Kebijakan Privasi"
        eyebrow="Dokumen Legal"
        intro="Kebijakan ini menjelaskan bagaimana {{ config('petshop.store.name') }} mengumpulkan, menggunakan, dan melindungi data pribadi Anda." />

    <section class="w-full py-12">
        <div class="max-w-4xl mx-auto px-6">
            <div class="surface-card p-6 lg:p-8">
                <div class="space-y-8">
                    @foreach ($sections as [$heading, $body])
                        <div>
                            <h2 class="font-title-md text-title-md text-on-surface font-bold mb-1.5">{{ $heading }}</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $body }}</p>
                        </div>
                    @endforeach
                </div>

                <p class="font-body-sm text-body-sm text-outline mt-10 pt-6 border-t border-surface-container-low">
                    Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <x-page-help />
        </div>
    </section>
</main>
@endsection