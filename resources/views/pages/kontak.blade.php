@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
        <x-page-header
            title="Hubungi PETCARE HOUSE"
            eyebrow="Kontak"
            icon="forum"
            intro="Punya pertanyaan soal produk, layanan, atau status pesanan? Tim kami siap membantu." />

        <section class="py-14 lg:py-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                <div class="p-5 sm:p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col h-full">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary-fixed text-primary mb-4 shrink-0">
                        <svg aria-hidden="true" class="w-[26px] h-[26px]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                    </span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">WhatsApp</h2>
                    <p class="font-label-md text-label-md text-primary font-bold">+62{{ config('petshop.store.whatsapp') }}</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-2">
                        Balasan tercepat. Tersedia pukul 08.00 - 20.00 WIB.
                    </p>
                </div>

                <div class="p-5 sm:p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col h-full">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-secondary-fixed text-secondary mb-4 shrink-0">
                        <span class="material-symbols-outlined text-[26px]">mail</span>
                    </span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">Email</h2>
                    <p class="font-label-md text-label-md text-primary font-bold break-words">{{ config('petshop.store.email') }}</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-2">
                        Untuk pertanyaan formal dan tawaran kerja sama.
                    </p>
                </div>

                <div class="p-5 sm:p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col h-full">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-tertiary-fixed text-tertiary-container mb-4 shrink-0">
                        <span class="material-symbols-outlined text-[26px]">location_on</span>
                    </span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">Toko Utama</h2>
                    <p class="font-label-md text-label-md text-primary font-bold">Braga, Bandung</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-2">
                        Buka Senin sampai Sabtu, pukul 09.00 - 20.00 WIB.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-14 lg:py-20 bg-surface-container-low">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-start">
                <div class="surface-card overflow-hidden">
                    <div class="h-52 sm:h-64 bg-gradient-to-br from-primary-fixed via-surface-container to-secondary-fixed flex items-center justify-center">
                        <span class="material-symbols-outlined text-[72px] text-primary/40">map</span>
                    </div>
                    <div class="p-6 md:p-8">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-3">PETCARE HOUSE Bandung</h2>
                        <address class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed not-italic">
                            Jl. Asia Afrika No. 12, Braga<br>
                            Sumur Bandung, Bandung 40111<br>
                            Jawa Barat, Indonesia
                        </address>
                        <div class="flex flex-wrap gap-2 mt-5">
                            <span class="badge bg-primary-fixed text-on-primary-fixed-variant">Senin - Sabtu</span>
                            <span class="badge bg-surface-container text-on-surface-variant">09.00 - 20.00</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="surface-card p-6 md:p-8">
                        <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary text-on-primary mb-4 shrink-0">
                            <span class="material-symbols-outlined text-[26px]">forum</span>
                        </span>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface font-extrabold tracking-tight mb-3">
                           Bicara Lewat WhatsApp
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">
                            Cara tercepat mendapatkan jawaban. Kirim pertanyaan Anda, sertakan nomor pesanan bila menyangkut
                            transaksi yang sedang berjalan.
                        </p>
                        <a href="https://wa.me/{{ config('petshop.store.whatsapp') }}" rel="noopener" target="_blank"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chat</span>
                            Buka WhatsApp
                        </a>
                    </div>

                    <div class="surface-card p-6 md:p-8">
                        <h2 class="font-headline-lg text-headline-lg text-on-surface font-extrabold tracking-tight mb-3">
                            Pertanyaan Umum
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">
                            Mungkin jawaban Anda sudah tersedia di pusat bantuan kami, mulai dari kebijakan pengiriman
                            sampai panduan grooming.
                        </p>
                        <a href="{{ route('bantuan-faq') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-[18px]">help</span>
                            Buka Pusat Bantuan
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection