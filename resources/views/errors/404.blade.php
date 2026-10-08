@extends('errors.layout')

@section('code', '404')
@section('icon', 'search_off')
@section('title', 'Halaman Tidak Ditemukan')
@section('message', 'Halaman yang Anda cari tidak ada atau sudah dipindahkan. Mungkin tautannya sudah tidak berlaku.')

@section('actions')
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('beranda') }}"
           class="px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all inline-flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">home</span>
            Kembali ke Beranda
        </a>
        <a href="{{ route('produk.index') }}"
           class="px-7 py-3.5 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-highest transition-all inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">storefront</span>
            Lihat Produk
        </a>
    </div>
@endsection
