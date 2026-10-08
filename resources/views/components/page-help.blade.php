@props([
    'title' => 'Belum menemukan jawabannya?',
    'description' => 'Tim kami siap membantu setiap hari, pukul 08.00 - 20.00 WIB.',
])

<div class="surface-card p-6 lg:p-8 flex flex-col items-center text-center">
    <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary-fixed text-primary mb-4">
        <span class="material-symbols-outlined text-[26px]">support_agent</span>
    </span>

    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-2">{{ $title }}</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mb-6">{{ $description }}</p>

    <div class="flex flex-wrap items-center justify-center gap-3">
        <a href="https://wa.me/{{ config('petshop.store.whatsapp') }}" rel="noopener" target="_blank"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
            <span class="material-symbols-outlined text-[18px]">chat</span>
            Chat WhatsApp
        </a>

        <a href="{{ route('kontak') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[18px]">mail</span>
            Halaman Kontak
        </a>
    </div>
</div>