@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="relative w-full overflow-hidden bg-gradient-to-b from-surface-container via-surface to-background">
        <div class="pointer-events-none absolute -top-32 -right-24 h-[26rem] w-[26rem] rounded-full bg-secondary/10 blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/3 -left-40 h-96 w-96 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-12 lg:py-16">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-2 lg:gap-14">

                {{-- ============ Showcase kiri (brand panel) ============ --}}
                <div class="elev-3 relative hidden min-h-[640px] flex-col justify-between overflow-hidden rounded-[28px] p-10 text-on-primary lg:flex xl:p-12"
                     style="background-image: radial-gradient(120% 120% at 97% 0%, #1a7a4b 0%, #0f5132 45%, #09331e 100%);">
                    <div class="pointer-events-none absolute -left-16 -top-24 h-80 w-80 rounded-full bg-tertiary-fixed/20 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-28 -right-20 h-72 w-72 rounded-full bg-secondary/40 blur-3xl"></div>
                    <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
                         style="background-image: radial-gradient(rgba(255,255,255,.9) 1px, transparent 1px); background-size: 24px 24px;"></div>

                    <div class="relative">
                        <div class="mb-7">
                            <span class="inline-flex items-center gap-2.5 rounded-full border border-white/15 bg-white/10 py-1.5 pl-1.5 pr-4 backdrop-blur-sm">
                                <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-white">
                                    <img src="{{ asset('images/logo.png') }}" alt="" class="h-6 w-6 object-contain">
                                </span>
                                <span class="font-label-caps uppercase tracking-[0.14em] text-tertiary-fixed font-bold">
                                    {{ config('petshop.store.name') }}
                                </span>
                            </span>
                        </div>

                        <span class="mb-5 inline-flex items-center gap-2 rounded-full bg-secondary px-3.5 py-1.5 text-on-primary">
                            <span class="material-symbols-outlined text-[18px]">card_giftcard</span>
                            <span class="font-label-caps uppercase font-bold">Voucher Rp 50.000</span>
                        </span>

                        <h2 class="font-headline-xl mb-4 font-extrabold leading-[1.15] tracking-tight text-on-primary">
                            Buat akun, dapatkan <span class="italic text-tertiary-fixed">voucher pertama</span> Anda.
                        </h2>
                        <p class="max-w-md font-body-md text-body-md leading-relaxed text-white/75">
                            Bergabung bersama ribuan Pet Parents yang mempercayakan perawatan Sahabatnya kepada kami.
                        </p>
                    </div>

                    {{-- Kartu voucher mock --}}
                    <div class="relative my-8">
                        <div class="elev-3 overflow-hidden rounded-[22px] bg-surface-container-lowest p-6 text-on-surface"
                             style="background-image: radial-gradient(rgba(15,81,50,.10) 1px, transparent 1px); background-size: 16px 16px;">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-label-caps uppercase tracking-widest text-outline font-bold">Voucher Selamat Datang</p>
                                    <p class="mt-1 font-body-sm text-body-sm text-outline">Khusus anggota baru {{ config('petshop.store.name') }}</p>
                                </div>
                            </div>

                            <p class="mt-4 font-display-lg text-headline-xl font-extrabold tracking-tight text-primary">
                                Rp50.000
                            </p>

                            <div class="my-4 flex items-center gap-3">
                                <span class="flex-1 border-t border-dashed border-surface-outline-app"></span>
                                <span class="material-symbols-outlined text-[18px] text-outline">confirmation_number</span>
                                <span class="flex-1 border-t border-dashed border-surface-outline-app"></span>
                            </div>

                            <div class="flex flex-wrap items-end justify-between gap-3">
                                <div>
                                    <p class="font-label-caps uppercase text-outline font-bold">Kode</p>
                                    <span class="mt-1 inline-block rounded-lg border border-dashed border-primary/40 bg-primary-fixed/50 px-3 py-1.5 font-mono text-body-sm font-bold tracking-widest text-primary">
                                        WELCOME50
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="font-label-caps uppercase text-outline font-bold">Min. Belanja</p>
                                    <p class="font-label-lg text-label-lg font-bold text-on-surface">Rp150.000</p>
                                </div>
                            </div>
                        </div>

                        <div class="elev-3 absolute -right-5 -top-5 flex -rotate-6 items-center gap-2 rounded-xl bg-secondary px-3.5 py-2 text-on-primary">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            <span class="font-label-lg text-label-lg font-bold">Otomatis Aktif</span>
                        </div>
                    </div>

                    {{-- Benefit membership --}}
                    <div class="relative grid grid-cols-2 gap-3">
                        @foreach ([
                            ['sell', 'Kupon Eksklusif', 'Setiap bulan untuk member'],
                            ['local_shipping', 'Prioritas Booking', 'Grooming & klinik'],
                            ['receipt_long', 'Lacak Real-time', 'Status pesanan akurat'],
                            ['support_agent', 'CS 24/7', 'Bantuan kapan saja'],
                        ] as [$icon, $title, $desc])
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-3.5 backdrop-blur-sm transition-colors hover:bg-white/15">
                                <span class="material-symbols-outlined mb-2 block text-[22px] text-tertiary-fixed">{{ $icon }}</span>
                                <p class="font-label-lg text-label-lg font-bold">{{ $title }}</p>
                                <p class="font-body-sm text-body-sm text-white/70">{{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="relative mt-7 flex items-center gap-2 border-t border-white/10 pt-5 font-body-sm text-body-sm text-white/70">
                        <span class="material-symbols-outlined text-[18px] text-tertiary-fixed">shield_lock</span>
                        Data Anda terenkripsi &amp; tidak pernah dibagikan ke pihak ketiga.
                    </div>
                </div>

                {{-- ============ Kartu form ============ --}}
                <div class="mx-auto w-full max-w-lg">
                    <div class="auth-enter elev-3 surface-card relative overflow-hidden rounded-[28px]">
                        <div class="h-1.5 w-full bg-gradient-to-r from-primary via-surface-tint to-secondary"></div>

                        <div class="p-6 sm:p-8 lg:p-10">
                            {{-- Brand ringkas (mobile) --}}
                            <div class="mb-6 flex items-center gap-3 lg:hidden">
                                <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-primary-fixed">
                                    <img src="{{ asset('images/logo.png') }}" alt="" class="h-7 w-7 object-contain">
                                </span>
                                <div>
                                    <p class="font-label-lg text-label-lg font-bold text-on-surface">{{ config('petshop.store.name') }}</p>
                                    <p class="font-body-sm text-body-sm text-outline">Pet Shop &amp; Clinic Service</p>
                                </div>
                            </div>

                            <div class="mb-7 flex items-start justify-between gap-4">
                                <div>
                                    <span class="mb-3 inline-flex items-center gap-1.5 rounded-full bg-secondary-fixed px-3 py-1 text-on-secondary-fixed-variant">
                                        <span class="material-symbols-outlined text-[16px]">card_giftcard</span>
                                        <span class="font-label-caps uppercase font-bold">Gratis Voucher Rp50.000</span>
                                    </span>
                                    <h1 class="font-headline-lg text-headline-lg font-bold tracking-tight text-on-surface">
                                        Buat Akun Baru
                                    </h1>
                                    <p class="mt-1.5 font-body-sm text-body-sm text-outline">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 font-semibold text-primary hover:underline">
                                            Masuk di sini
                                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                        </a>
                                    </p>
                                </div>
                                <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-secondary-fixed text-secondary sm:flex">
                                    <span class="material-symbols-outlined text-[24px]">pets</span>
                                </span>
                            </div>

                            <form action="{{ route('register') }}" method="POST" class="space-y-4" data-busy data-busy-text="Membuat akun...">
                                @csrf

                                {{-- Identitas --}}
                                <div class="flex items-center gap-2.5 pt-1">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-fixed text-primary">
                                        <span class="material-symbols-outlined text-[16px]">badge</span>
                                    </span>
                                    <p class="font-label-caps uppercase text-outline">Identitas</p>
                                    <span class="h-px flex-1 bg-surface-container-low"></span>
                                </div>

                                <x-form-field name="name" label="Nama Lengkap" required
                                              :value="old('name')" placeholder="Nama sesuai identitas"
                                              icon="person" autocomplete="name" />

                                <x-form-field name="email" label="Email" type="email" required
                                              :value="old('email')" placeholder="nama@email.com"
                                              icon="mail" autocomplete="email" />

                                <x-form-field name="phone" label="Nomor WhatsApp"
                                              :value="old('phone')" placeholder="08xxxxxxxxxx"
                                              icon="smartphone" autocomplete="tel"
                                              hint="Dipakai untuk konfirmasi pesanan dan booking." />

                                {{-- Keamanan --}}
                                <div class="flex items-center gap-2.5 pt-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-fixed text-primary">
                                        <span class="material-symbols-outlined text-[16px]">shield</span>
                                    </span>
                                    <p class="font-label-caps uppercase text-outline">Keamanan Akun</p>
                                    <span class="h-px flex-1 bg-surface-container-low"></span>
                                </div>

                                <div>
                                    <x-form-field name="password" label="Password" type="password" required
                                                  placeholder="Minimal 8 karakter"
                                                  icon="lock" autocomplete="new-password" />

                                    {{-- Meter kekuatan password --}}
                                    <div class="mt-2.5 rounded-xl border border-surface-outline-app/70 bg-surface-container-low/60 p-3" data-password-strength>
                                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-surface-outline-app/70">
                                            <div data-strength-bar class="h-full w-0 rounded-full bg-error transition-all duration-300"></div>
                                        </div>

                                        <div class="mt-2.5 flex flex-wrap items-center justify-between gap-x-3 gap-y-2">
                                            <p class="font-label-md text-label-md text-outline">
                                                Kekuatan password:
                                                <span data-strength-label class="font-bold text-on-surface">Belum diisi</span>
                                            </p>
                                            <ul class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                                @foreach ([
                                                    'length' => 'Minimal 8 karakter',
                                                    'letter' => 'Ada huruf',
                                                    'number' => 'Ada angka',
                                                ] as $key => $rule)
                                                    <li class="flex items-center gap-1 font-label-md text-label-md text-outline transition-colors"
                                                        data-strength-rule="{{ $key }}">
                                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                                        <span>{{ $rule }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <x-form-field name="password_confirmation" label="Konfirmasi Password" type="password" required
                                              placeholder="Ulangi password"
                                              icon="lock" autocomplete="new-password" />

                                {{-- Persetujuan --}}
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-surface-outline-app/70 bg-surface-container-low/60 p-3.5 transition-all has-[:checked]:border-primary/40 has-[:checked]:bg-primary-fixed/40">
                                    <input type="checkbox" required
                                           class="mt-0.5 h-5 w-5 shrink-0 cursor-pointer rounded border-2 border-surface-outline-app accent-primary">
                                    <span class="font-body-sm text-body-sm leading-relaxed text-on-surface-variant">
                                        Saya menyetujui
                                        <a href="{{ route('syarat-ketentuan') }}" class="font-semibold text-primary hover:underline">Syarat &amp; Ketentuan</a>
                                        serta
                                        <a href="{{ route('kebijakan-privasi') }}" class="font-semibold text-primary hover:underline">Kebijakan Privasi</a>
                                        {{ config('petshop.store.name') }}.
                                    </span>
                                </label>

                                <button type="submit"
                                        class="group w-full rounded-xl bg-primary py-4 font-label-lg text-label-lg font-bold text-on-primary shadow-[0_12px_24px_-12px_rgba(15,81,50,0.7)] transition-all hover:bg-primary-container hover:shadow-lg active:scale-[0.99] focus:outline-none focus-visible:ring-4 focus-visible:ring-primary/25">
                                    <span class="flex items-center justify-center gap-2">
                                        <span>Buat Akun Gratis</span>
                                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">person_add</span>
                                    </span>
                                </button>

                                <p class="flex items-center justify-center gap-1.5 pt-1 font-body-sm text-body-sm text-outline">
                                    <span class="material-symbols-outlined text-[16px]">lock</span>
                                    Data terenkripsi &amp; enkripsi SSL 256-bit
                                </p>
                            </form>

                            <div class="mt-6 flex items-start gap-2.5 rounded-2xl bg-primary-fixed/50 p-3.5 text-primary">
                                <span class="material-symbols-outlined mt-px text-[18px]">info</span>
                                <p class="font-body-sm text-body-sm leading-relaxed">
                                    Voucher <span class="font-bold">Rp50.000</span> otomatis aktif setelah email terverifikasi.
                                    Berlaku 30 hari untuk pesanan minimal Rp150.000.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="mt-5 text-center font-body-sm text-body-sm text-outline">
                        <span class="material-symbols-outlined align-[-4px] text-[16px]">groups</span>
                        Bergabung dengan 2.400+ Pet Parents di seluruh Indonesia
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
