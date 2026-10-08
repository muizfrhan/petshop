<section class="w-full py-10 bg-surface-container-lowest">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="rounded-3xl bg-gradient-to-r from-primary via-primary-container to-tertiary p-8 lg:p-12 text-on-primary shadow-xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-white/5 blur-2xl"></div>
            <div class="absolute -left-12 -bottom-12 w-64 h-64 rounded-full bg-secondary/20 blur-xl"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary text-on-primary font-label-caps text-label-caps uppercase font-bold mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">bolt</span>
                        <span>Flash Sale Akhir Pekan</span>
                    </div>

                    <h2 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl font-bold tracking-tight mb-3">
                        Diskon Spesial Hingga 30% Makanan &amp; Snack
                    </h2>

                    <p class="font-body-md text-body-md text-white/85 max-w-lg mb-6">
                        Beli 2 Dapatkan 1 Gratis untuk snack pilihan! Ditambah gratis ongkos kirim ke seluruh area
                        dengan transaksi minimal Rp150.000.
                    </p>

                    <div class="flex items-center gap-3 text-on-primary">
                        <span class="font-label-caps text-label-caps uppercase tracking-wider text-white/70 font-bold">Berakhir Dalam:</span>
                        <div class="flex items-center gap-2 font-mono font-bold">
                            <div class="flex flex-col items-center bg-white/20 backdrop-blur-md rounded-xl px-3 py-1.5 min-w-[50px]">
                                <span class="font-headline-sm text-headline-sm" id="countdown-hours">08</span>
                                <span class="font-label-caps text-[10px] text-white/70">JAM</span>
                            </div>
                            <span class="font-bold">:</span>
                            <div class="flex flex-col items-center bg-white/20 backdrop-blur-md rounded-xl px-3 py-1.5 min-w-[50px]">
                                <span class="font-headline-sm text-headline-sm" id="countdown-minutes">45</span>
                                <span class="font-label-caps text-[10px] text-white/70">MENIT</span>
                            </div>
                            <span class="font-bold">:</span>
                            <div class="flex flex-col items-center bg-secondary text-on-primary rounded-xl px-3 py-1.5 min-w-[50px] shadow-sm">
                                <span class="font-headline-sm text-headline-sm" id="countdown-seconds">19</span>
                                <span class="font-label-caps text-[10px] text-white/70 opacity-80">DETIK</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 flex justify-center lg:justify-end">
                    <div class="bg-white/10 backdrop-blur-lg p-6 rounded-3xl w-full max-w-sm shadow-inner flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[32px] text-white/80">local_shipping</span>
                            <div>
                                <p class="font-title-md text-title-md font-bold text-on-primary">Bebas Ongkir Instant</p>
                                <p class="font-body-sm text-body-sm text-white/85">Kirim via GoSend &amp; GrabExpress</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[32px] text-tertiary-fixed">redeem</span>
                            <div>
                                <p class="font-title-md text-title-md font-bold text-on-primary">Kode Kupon Berlokasi</p>
                                <p class="font-body-sm text-body-sm text-white/85">Gunakan pada menu keranjang</p>
                            </div>
                        </div>

                        <form action="{{ route('keranjang.kupon') }}" method="POST" class="flex gap-2 mt-2">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="Masukkan kode" required
                                   class="flex-1 h-11 px-3 rounded-xl bg-surface-container-lowest/90 text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <button class="px-4 h-11 rounded-xl bg-secondary text-on-primary font-label-md text-label-md font-bold hover:bg-secondary/90 shadow-md transition-all whitespace-nowrap">
                                Klaim
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>