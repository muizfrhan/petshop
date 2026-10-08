<footer class="w-full bg-surface-container-low text-on-surface-variant pt-space-2xl pb-space-lg mt-auto">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-xl mb-space-2xl">

        <div>
            <div class="flex items-center gap-2 mb-4">
                <img alt="Logo" class="h-8 w-auto object-contain" src="{{ asset('images/logo.png') }}">
                <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ config('petshop.store.name') }}</span>
            </div>
            <p class="font-body-sm text-body-sm text-outline leading-relaxed mb-6">
                Destinasi terpercaya untuk nutrisi premium, perlengkapan bermain, grooming profesional, dan
                perawatan kesehatan hewan kesayangan Anda di bawah pengawasan dokter hewan berpengalaman.
            </p>
            <div class="flex items-center gap-3">
                @foreach ([
                    ['photo_camera', 'Instagram'],
                    ['video_library', 'TikTok'],
                    ['public', 'Facebook'],
                    ['smart_display', 'YouTube'],
                ] as [$icon, $label])
                    <span aria-label="{{ $label }}"
                          class="w-9 h-9 rounded-full bg-surface-container-lowest flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                    </span>
                @endforeach
            </div>
        </div>

        <div>
            <h4 class="font-title-md text-title-md text-on-surface font-semibold mb-4">Belanja Cepat</h4>
            <ul class="space-y-2.5 font-body-sm text-body-sm">
                @php
                    $quickLinks = [
                        'Makanan Kucing Premium' => ['produk.index', ['kategori' => 'makanan-kucing']],
                        'Nutrisi Anjing Spesifik' => ['produk.index', ['kategori' => 'makanan-anjing']],
                        'Vitamin & Suplemen' => ['produk.index', ['q' => 'vitamin']],
                        'Pasir Gumpal & Litter Box' => ['produk.index', ['q' => 'pasir']],
                        'Mainan Interaktif' => ['produk.index', ['q' => 'mainan']],
                    ];
                @endphp
                @foreach ($quickLinks as $label => [$routeName, $params])
                    <li class="flex items-center">
                        <a class="hover:text-primary transition-colors" href="{{ route($routeName, $params) }}">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h4 class="font-title-md text-title-md text-on-surface font-semibold mb-4">Layanan &amp; Care</h4>
            <ul class="space-y-2.5 font-body-sm text-body-sm">
                @foreach ([
                    'Grooming Salon & Spa' => 'layanan.index',
                    'Konsultasi & Vaksinasi Vet' => 'layanan.index',
                    'Pet Hotel & Penitipan Sehat' => 'layanan.index',
                    'Antar-Jemput Pet Care' => 'layanan.index',
                    'Kontak Darurat 24 Jam' => 'kontak',
                ] as $label => $routeName)
                    <li class="flex items-center">
                        <a class="hover:text-primary transition-colors" href="{{ route($routeName) }}">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h4 class="font-title-md text-title-md text-on-surface font-semibold mb-4">Dapatkan Tips &amp; Diskon</h4>
            <p class="font-body-sm text-body-sm text-outline mb-3">
                Daftar buletin bulanan kami untuk voucher Rp 50.000 pertama dan panduan nutrisi gratis.
            </p>

            <form action="{{ route('langganan.store') }}" method="POST" class="space-y-2">
                @csrf
                <input class="w-full h-10 px-3 rounded-lg bg-surface-container-lowest text-on-surface font-body-sm text-body-sm placeholder:text-outline focus:outline-none focus:ring-1 focus:ring-primary"
                       name="email"
                       placeholder="Alamat email Anda..."
                       type="email"
                       required
                       value="{{ old('email') }}">
                @error('email')
                    <p class="font-body-sm text-body-sm text-error flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                    </p>
                @enderror
                <button class="w-full h-10 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-primary-container transition-colors" type="submit">
                    Langganan Newsletter
                </button>
            </form>

            <div class="mt-4">
                <p class="font-label-caps text-label-caps text-outline uppercase mb-2">Dukungan Pembayaran &amp; Kurir</p>
                <div class="flex flex-wrap gap-2 text-outline font-label-md text-label-md">
                    @foreach (['BCA', 'Mandiri', 'QRIS', 'GoSend', 'JNE'] as $channel)
                        <span class="px-2 py-1 bg-surface-container-lowest rounded">{{ $channel }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-space-lg flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="font-body-sm text-body-sm text-outline text-center sm:text-left">
            &copy; {{ now()->year }} {{ config('petshop.store.name') }}. Semua hak dilindungi.
        </p>
        <div class="flex items-center gap-6 font-label-md text-label-md text-outline">
            <a class="hover:text-on-surface" href="{{ route('syarat-ketentuan') }}">Syarat &amp; Ketentuan</a>
            <a class="hover:text-on-surface" href="{{ route('kebijakan-privasi') }}">Kebijakan Privasi</a>
            <a class="hover:text-on-surface" href="{{ route('bantuan-faq') }}">Bantuan &amp; FAQ</a>
        </div>
    </div>
</footer>

<a aria-label="Chat WhatsApp Bantuan"
   class="fixed bottom-6 right-6 z-40 flex items-center gap-2 bg-[#0d7f40] text-white px-4 py-3 rounded-full shadow-[0_8px_24px_-4px_rgba(15,23,42,0.15)] hover:scale-105 active:scale-95 transition-all group"
   href="https://wa.me/{{ config('petshop.store.whatsapp') }}?text={{ rawurlencode('Halo PETCARE HOUSE, saya ingin bertanya mengenai produk dan layanan yang tersedia.') }}"
   rel="noopener noreferrer"
   target="_blank">
    <span class="material-symbols-outlined text-[24px]">chat</span>
    <span class="font-label-lg text-label-lg font-bold pr-1 hidden sm:inline">Konsultasi &amp; Tanya</span>
</a>