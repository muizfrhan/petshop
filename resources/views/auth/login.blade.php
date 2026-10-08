@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="relative w-full overflow-hidden bg-gradient-to-b from-surface-container via-surface to-background">
        <div class="pointer-events-none absolute -top-32 -left-24 h-[26rem] w-[26rem] rounded-full bg-primary/10 blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/3 -right-40 h-96 w-96 rounded-full bg-secondary/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-12 lg:py-16">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-2 lg:gap-14">

                {{-- ============ Showcase kiri (brand panel) ============ --}}
                <div class="elev-3 relative hidden min-h-[640px] flex-col justify-between overflow-hidden rounded-[28px] p-10 text-on-primary lg:flex xl:p-12"
                     style="background-image: radial-gradient(120% 120% at 3% 0%, #1a7a4b 0%, #0f5132 45%, #09331e 100%);">
                    <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full bg-tertiary-fixed/20 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-28 -left-20 h-72 w-72 rounded-full bg-secondary/30 blur-3xl"></div>
                    <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
                         style="background-image: radial-gradient(rgba(255,255,255,.9) 1px, transparent 1px); background-size: 24px 24px;"></div>

                    {{-- Brand --}}
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

                        <span class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1.5 text-tertiary-fixed">
                            <span class="material-symbols-outlined text-[18px]">verified_user</span>
                            <span class="font-label-caps uppercase font-bold">Selamat Datang Kembali</span>
                        </span>

                        <h2 class="font-headline-xl mb-4 font-extrabold leading-[1.15] tracking-tight text-on-primary">
                            Masuk untuk <span class="italic text-tertiary-fixed">melanjutkan</span> perawatan Sahabatmu.
                        </h2>
                        <p class="max-w-md font-body-md text-body-md leading-relaxed text-white/75">
                            Akses riwayat pesanan, wishlist, dan booking layanan perawatan dalam satu akun yang aman.
                        </p>
                    </div>

                    {{-- Foto + kartu melayang --}}
                    <div class="relative my-8">
                        <div class="relative aspect-[16/10] w-full overflow-hidden rounded-[22px] border border-white/15 bg-white/10 shadow-2xl">
                            <img src="{{ asset('images/hero-card.jpg') }}" alt="Perawatan hewan kesayangan di PETCARE HOUSE"
                                 class="h-full w-full object-cover" width="640" height="400">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/85 via-primary/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-5 right-5">
                                <span class="font-label-caps uppercase tracking-widest text-tertiary-fixed font-bold">Perawatan Terintegrasi</span>
                                <p class="max-w-[58%] font-title-md text-title-md font-bold leading-snug text-white">Kesejahteraan Hewan Adalah Prioritas Kami</p>
                            </div>
                        </div>

                        <div class="elev-3 absolute -left-6 top-6 flex items-center gap-3 rounded-2xl bg-surface-container-lowest p-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-surface-tint text-on-primary">
                                <span class="material-symbols-outlined text-[22px]">medical_services</span>
                            </span>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span>
                                    <p class="font-label-md text-label-md font-bold text-on-surface">Klinik Siaga</p>
                                </div>
                                <p class="font-body-sm text-body-sm text-outline">Konsultasi Dokter Aktif</p>
                            </div>
                        </div>

                        <div class="elev-3 absolute -top-7 right-5 flex items-center gap-3 rounded-2xl bg-secondary px-4 py-3 text-on-primary shadow-xl">
                            <span class="material-symbols-outlined text-[24px]">local_fire_department</span>
                            <div>
                                <p class="font-label-caps uppercase font-bold tracking-wider opacity-90">Voucher Perdana</p>
                                <p class="font-label-lg font-bold">Potongan Rp50.000</p>
                            </div>
                        </div>
                    </div>

                    {{-- Benefit --}}
                    <div class="relative grid grid-cols-3 gap-3">
                        @foreach ([
                            ['shopping_bag', 'Lacak Pesanan', 'Sampai depan pintu'],
                            ['favorite', 'Wishlist', 'Simpan favoritmu'],
                            ['calendar_month', 'Booking Mudah', 'Grooming & klinik'],
                        ] as [$icon, $title, $desc])
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-3.5 backdrop-blur-sm transition-colors hover:bg-white/15">
                                <span class="material-symbols-outlined mb-2 block text-[22px] text-tertiary-fixed">{{ $icon }}</span>
                                <p class="font-label-lg text-label-lg font-bold">{{ $title }}</p>
                                <p class="font-body-sm text-body-sm text-white/70">{{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Trust --}}
                    <div class="relative mt-7 flex items-center gap-2 border-t border-white/10 pt-5 font-body-sm text-body-sm text-white/70">
                        <span class="material-symbols-outlined text-[18px] text-tertiary-fixed">shield_lock</span>
                        Data Anda terenkripsi &amp; tidak pernah dibagikan ke pihak ketiga.
                    </div>
                </div>

                {{-- ============ Kartu form ============ --}}
                <div class="mx-auto w-full max-w-lg">
                    <div class="auth-enter elev-3 surface-card relative overflow-hidden rounded-[28px]">
                        <div class="h-1.5 w-full bg-gradient-to-r from-primary via-surface-tint to-tertiary"></div>

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
                                    <span class="mb-3 inline-flex items-center gap-1.5 rounded-full bg-primary-fixed/60 px-3 py-1 text-primary">
                                        <span class="material-symbols-outlined text-[16px]">login</span>
                                        <span class="font-label-caps uppercase font-bold">Masuk Akun</span>
                                    </span>
                                    <h1 class="font-headline-lg text-headline-lg font-bold tracking-tight text-on-surface">
                                        Senang bertemu lagi.
                                    </h1>
                                    <p class="mt-1.5 font-body-sm text-body-sm text-outline">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1 font-semibold text-primary hover:underline">
                                            Daftar sekarang
                                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                        </a>
                                    </p>
                                </div>
                                <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary-fixed text-primary sm:flex">
                                    <span class="material-symbols-outlined text-[24px]">pets</span>
                                </span>
                            </div>

                            @if (session('status'))
                                <x-alert variant="success" class="mb-5">{{ session('status') }}</x-alert>
                            @endif

                            @error('email')
                                <x-alert variant="error" class="mb-5">{{ $message }}</x-alert>
                            @enderror

                            <form action="{{ route('login') }}" method="POST" class="space-y-4" data-busy data-busy-text="Masuk...">
                                @csrf

                                <x-form-field name="email" label="Email" type="email" required
                                              :value="old('email')" placeholder="nama@email.com"
                                              icon="mail" autocomplete="email" />

                                <div>
                                    <div class="mb-1.5 flex items-center justify-between">
                                        <label class="form-label !mb-0" for="field-password">
                                            Password
                                            <span class="text-error" aria-hidden="true">*</span>
                                        </label>
                                        <a href="{{ route('password.request') }}" class="font-label-md text-label-md font-semibold text-primary transition-colors hover:text-primary-container">
                                            Lupa password?
                                        </a>
                                    </div>

                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] text-outline">lock</span>
                                        <input type="password" id="field-password" name="password" required
                                               class="form-input pl-11 pr-12" placeholder="Masukkan password"
                                               autocomplete="current-password"
                                               @error('password') aria-invalid="true" @enderror>
                                        <button type="button"
                                                class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-outline transition-colors hover:bg-surface-container-low hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30"
                                                data-password-toggle="field-password"
                                                aria-label="Tampilkan password" aria-controls="field-password">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                    </div>

                                    @error('password')
                                        <p class="mt-1.5 flex items-center gap-1 font-body-sm text-body-sm text-error">
                                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="flex flex-col gap-3 rounded-2xl border border-surface-outline-app/70 bg-surface-container-low/60 p-3.5 sm:flex-row sm:items-center sm:justify-between">
                                    <label class="group flex cursor-pointer items-center gap-3 select-none">
                                        <input type="checkbox" name="remember" value="1" class="peer sr-only" @checked(old('remember'))>
                                        <span class="relative h-6 w-11 shrink-0 rounded-full bg-surface-outline-app/80 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-transform after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-5"></span>
                                        <span class="font-body-sm text-body-sm text-on-surface-variant transition-colors group-hover:text-on-surface">
                                            Ingat saya di perangkat ini
                                        </span>
                                    </label>

                                    <span class="hidden items-center gap-1.5 font-label-md text-label-md text-outline sm:flex">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Sesi 120 menit
                                    </span>
                                </div>

                                <button type="submit"
                                        class="group w-full rounded-xl bg-primary py-4 font-label-lg text-label-lg font-bold text-on-primary shadow-[0_12px_24px_-12px_rgba(15,81,50,0.7)] transition-all hover:bg-primary-container hover:shadow-lg active:scale-[0.99] focus:outline-none focus-visible:ring-4 focus-visible:ring-primary/25">
                                    <span class="flex items-center justify-center gap-2">
                                        <span>Masuk ke Akun</span>
                                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                                    </span>
                                </button>

                                <p class="flex items-center justify-center gap-1.5 pt-1 font-body-sm text-body-sm text-outline">
                                    <span class="material-symbols-outlined text-[16px]">lock</span>
                                    Login aman dengan enkripsi SSL 256-bit
                                </p>
                            </form>

                            {{-- Akun demo --}}
                            <div class="mt-7 border-t border-surface-container-low pt-6">
                                <div class="mb-3 flex items-center justify-between gap-2">
                                    <p class="font-label-caps uppercase text-outline">Akun demo (development)</p>
                                    <span class="hidden rounded-full bg-secondary-fixed px-2.5 py-1 font-label-caps uppercase text-on-secondary-fixed-variant sm:inline-flex">Klik untuk isi</span>
                                </div>

                                <div class="grid gap-2">
                                    @foreach ([
                                        ['admin_panel_settings', 'Admin', 'admin@petcarehouse.test'],
                                        ['shopping_bag', 'Pelanggan', 'budi@mail.test'],
                                    ] as [$icon, $role, $email])
                                        <button type="button"
                                                class="group flex w-full items-center justify-between gap-3 rounded-xl border border-transparent bg-surface-container-low px-3.5 py-3 text-left transition-all hover:border-primary/25 hover:bg-primary-fixed/40"
                                                data-demo-fill
                                                data-demo-email="{{ $email }}"
                                                data-demo-password="password">
                                            <span class="flex min-w-0 items-center gap-3">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-surface-container-lowest text-primary">
                                                    <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                                </span>
                                                <span class="min-w-0">
                                                    <span class="block font-label-lg text-label-lg font-bold text-on-surface">{{ $role }}</span>
                                                    <span class="block truncate font-body-sm text-body-sm text-outline">{{ $email }}</span>
                                                </span>
                                            </span>
                                            <span class="flex shrink-0 items-center gap-1 font-label-md font-semibold text-primary opacity-0 transition-opacity group-hover:opacity-100">
                                                Isi otomatis
                                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>

                                <p class="mt-3 text-center font-body-sm text-body-sm text-outline">
                                    Password semua akun demo: <span class="font-mono font-semibold text-on-surface">password</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="mt-5 text-center font-body-sm text-body-sm text-outline">
                        <span class="material-symbols-outlined align-[-4px] text-[16px]">verified_user</span>
                        Dengan masuk, Anda menyetujui
                        <a href="{{ route('syarat-ketentuan') }}" class="font-semibold text-primary hover:underline">Syarat &amp; Ketentuan</a>
                        kami.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
