@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-16">
        <div class="max-w-md mx-auto px-6 text-center">
            <div class="w-16 h-16 rounded-2xl bg-secondary-fixed flex items-center justify-center text-secondary mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px]">mark_email_read</span>
            </div>

            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mb-3">
                Verifikasi Email Anda
            </h1>
            <p class="font-body-md text-body-md text-outline leading-relaxed mb-8">
                Kami sudah mengirimkan tautan verifikasi ke
                <span class="font-semibold text-on-surface">{{ auth()->user()->email }}</span>.
                Silakan klik tautan tersebut untuk mengaktifkan akun.
            </p>

            @if (session('success'))
                <x-alert variant="success" class="mb-5 text-left">{{ session('success') }}</x-alert>
            @endif

            <form action="{{ route('verification.resend') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="w-full py-3.5 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all shadow-md">
                    Kirim Ulang Tautan Verifikasi
                </button>
            </form>

            <a href="{{ route('akun.index') }}"
               class="w-full py-3.5 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block">
                Lewati Sementara
            </a>
        </div>
    </section>
</main>
@endsection