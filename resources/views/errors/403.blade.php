@extends('errors.layout')

@section('code', '403')
@section('icon', 'lock')
@section('title', 'Akses Ditolak')
@section('message', $exception?->getMessage() ?: 'Anda tidak memiliki izin untuk membuka halaman ini. jika ini tidak sengaja, silakan masuk dengan akun yang sesuai.')

@section('actions')
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('login') }}"
           class="px-7 py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all inline-flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">login</span>
            Masuk ke Akun Saya
        </a>
        <a href="{{ route('beranda') }}"
           class="px-7 py-3.5 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-highest transition-all inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">home</span>
            Kembali ke Beranda
        </a>
    </div>
@endsection
