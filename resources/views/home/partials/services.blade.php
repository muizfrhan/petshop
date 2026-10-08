<section class="w-full py-16 bg-surface-container-low" id="layanan-unggulan">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Perawatan Profesional</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1">Layanan Care Terpadu</h2>
            </div>
            <a href="{{ route('layanan.index') }}"
               class="shrink-0 px-5 py-2.5 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-on-surface font-label-lg text-label-lg font-semibold hover:bg-surface-container-low transition-colors inline-flex items-center gap-2">
                Semua Layanan
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        @if ($services->isEmpty())
            <x-empty-state
                title="Belum ada layanan aktif"
                description="Layanan yang tersedia akan tampil di sini."
                icon="medical_services" />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($services as $index => $service)
                    <x-service-card :service="$service" :index="$index" />
                @endforeach
            </div>
        @endif
    </div>
</section>