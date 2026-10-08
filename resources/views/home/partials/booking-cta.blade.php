<section class="w-full py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="rounded-3xl bg-surface-container-lowest border border-surface-outline-app shadow-card overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8 lg:p-12 items-center">
                <div>
                    <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Booking Cepat</span>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1 mb-3">
                        Rawalkan Sahabatmu Tanpa Antre
                    </h2>
                    <p class="font-body-md text-body-md text-outline leading-relaxed mb-6">
                        Isi formulir singkat di bawah ini, tim kami akan menghubungi Anda untuk
                        konfirmasi jadwal grooming, perawatan, atau penanganan medis.
                    </p>

                    <div class="space-y-3">
                        @foreach ([
                            ['calendar_month', 'Jadwal fleksibel sesuai waktu Anda'],
                            ['notifications_active', 'Konfirmasi via WhatsApp'],
                            ['verified', 'Ditangani groomer & dokter hewan bersertifikat'],
                        ] as [$icon, $text])
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[20px] text-primary shrink-0">{{ $icon }}</span>
                                <span class="font-body-sm text-body-sm text-on-surface">{{ $text }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <a href="{{ route('booking.create') }}"
                       class="flex-1 px-6 py-4 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-md">
                        <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                        Buat Booking
                    </a>
                    <a href="{{ route('layanan.index') }}"
                       class="flex-1 px-6 py-4 rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-highest transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-secondary">medical_services</span>
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>