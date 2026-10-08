@extends('layouts.app')

@section('title', $service->name)

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="[
                'Layanan' => route('layanan.index'),
                $service->name => null,
            ]" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-14">
                <div class="lg:col-span-2">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary mb-5">
                        <span class="material-symbols-outlined text-[32px]">{{ $service->icon ?: 'spa' }}</span>
                    </div>

                    <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mb-4">
                        {{ $service->name }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md font-semibold">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            {{ $service->durationLabel() }}
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md font-semibold">
                            <span class="material-symbols-outlined text-[16px]">group</span>
                            {{ $service->bookings_count }} booking
                        </span>
                    </div>

                    <div class="prose-pch text-on-surface-variant leading-relaxed mb-8">
                        {{ $service->description }}
                    </div>

                    @if ($service->includeList())
                        <div class="surface-card p-6 mb-8">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">
                                Yang Anda Dapatkan
                            </h2>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($service->includeList() as $item)
                                    <li class="flex items-start gap-3">
                                        <span class="material-symbols-outlined text-[18px] text-primary shrink-0 mt-px">check_circle</span>
                                        <span class="font-body-sm text-body-sm text-on-surface">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($others->isNotEmpty())
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Layanan Lainnya</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach ($others as $index => $other)
                                    <x-service-card :service="$other" :index="$index" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="lg:col-span-1">
                    <div class="surface-card p-6 lg:sticky lg:top-24">
                        <p class="font-label-caps text-label-caps text-outline uppercase">Mulai Dari</p>
                        <p class="font-display-lg text-display-lg-mobile text-primary font-bold mb-1">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </p>
                        <p class="font-body-sm text-body-sm text-outline mb-6">per sesi layanan</p>

                        <a href="{{ route('booking.create', ['layanan' => $service->slug]) }}"
                           class="w-full py-4 rounded-xl bg-secondary text-on-primary font-headline-sm text-headline-sm font-bold hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 shadow-md">
                            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                            Booking Layanan
                        </a>

                        <div class="mt-6 pt-6 border-t border-surface-container-low space-y-3">
                            @foreach ([
                                ['event_available', 'Jadwal fleksibel sesuai kebutuhan'],
                                ['notifications_active', 'Konfirmasi via WhatsApp'],
                                ['payments', 'Bayar di tempat setelah layanan selesai'],
                            ] as [$icon, $text])
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[18px] text-primary shrink-0 mt-px">{{ $icon }}</span>
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $text }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection