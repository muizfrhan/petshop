@extends('errors.layout')

@section('code', '419')
@section('icon', 'timer_off')
@section('title', 'Sesi Kedaluwarsa')
@section('message', 'Sesi Anda sudah berakhir atau halaman terlalu lama terbuka. Silakan muat ulang dan coba kembali agar data Anda tidak hilang.')

@section('actions')
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button type="button" onclick="window.location.reload()"
                class="px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all inline-flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">refresh</span>
            Muat Ulang Halaman
        </button>
        <a href="{{ route('beranda') }}"
           class="px-7 py-3.5 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-highest transition-all inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">home</span>
            Kembali ke Beranda
        </a>
    </div>
@endsection
