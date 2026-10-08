@extends('layouts.app')

@section('title', 'Pesanan '.$order->order_number)

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="[
                'Pesanan' => route('pesanan.index'),
                $order->order_number => null,
            ]" />

            <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
                <div>
                    <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight">
                        {{ $order->order_number }}
                    </h1>
                    <p class="font-body-sm text-body-sm text-outline mt-1">
                        Dibuat {{ $order->created_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>
                <x-status-badge :status="$order->status" />
            </div>

            {{-- Timeline status --}}
            <div class="surface-card p-6 mb-8">
                @php
                    $timeline = [
                        ['pending', 'Pesanan Dibuat', 'Pesanan diterima dan menunggu pembayaran'],
                        ['paid', 'Pembayaran Diterima', 'Pembayaran sudah dikonfirmasi'],
                        ['processing', 'Diproses', 'Pesanan sedang disiapkan'],
                        ['shipped', 'Dikirim', 'Pesanan dalam perjalanan'],
                        ['completed', 'Selesai', 'Pesanan telah diterima'],
                    ];
                    $orderIndex = array_search($order->status, array_column($timeline, 0), true);
                    $isCancelled = $order->status === 'cancelled';
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-0">
                    @foreach ($timeline as $i => [$key, $title, $desc])
                        @php
                            $done = ! $isCancelled && $orderIndex !== false && $i <= $orderIndex;
                            $current = ! $isCancelled && $orderIndex === $i;
                        @endphp
                        <div class="flex items-center gap-4 sm:flex-1">
                            <div class="flex flex-col items-center shrink-0">
                                <div @class([
                                    'w-10 h-10 rounded-full flex items-center justify-center transition-colors',
                                    'bg-primary text-on-primary' => $done,
                                    'bg-surface-container text-outline' => ! $done,
                                    'ring-4 ring-primary/20' => $current,
                                ])>
                                    <span class="material-symbols-outlined text-[20px]">{{ $done ? 'check' : 'radio_button_unchecked' }}</span>
                                </div>
                                <p @class([
                                    'font-label-md text-label-md mt-2 text-center max-w-[8rem]',
                                    'font-bold text-on-surface' => $done,
                                    'text-outline' => ! $done,
                                ])>{{ $title }}</p>
                            </div>

                            @if (! $loop->last)
                                <div @class([
                                    'hidden sm:block flex-1 h-0.5 mx-2 mb-6',
                                    'bg-primary' => $done && $orderIndex !== false && $i < $orderIndex,
                                    'bg-surface-container' => ! ($done && $orderIndex !== false && $i < $orderIndex),
                                ])></div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($isCancelled)
                    <div class="mt-6 pt-5 border-t border-surface-container-low">
                        <p class="flex items-center gap-2 font-body-sm text-body-sm text-error">
                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                            Pesanan ini telah dibatalkan.
                        </p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Produk --}}
                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Produk Dipesan</h2>

                        <ul class="space-y-4">
                            @foreach ($order->items as $item)
                                <li class="flex items-center gap-4">
                                    <img src="{{ $item->product?->imageUrl() ?? asset('images/hero-card.jpg') }}"
                                         alt="{{ $item->product_name }}"
                                         class="w-16 h-16 rounded-xl object-cover bg-surface-container-low shrink-0" loading="lazy">

                                    <div class="min-w-0 flex-1">
                                        <p class="font-title-md text-title-md text-on-surface font-semibold line-clamp-1">
                                            {{ $item->product_name }}
                                        </p>
                                        <p class="font-body-sm text-body-sm text-outline">
                                            Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <span class="font-label-lg text-label-lg font-bold text-on-surface shrink-0">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        @if ($order->canBeReviewed() && auth()->check())
                            <div class="mt-6 pt-5 border-t border-surface-container-low">
                                <p class="font-body-sm text-body-sm text-outline mb-3">
                                    Sudah menerima pesanan? Berikan ulasan untuk produk yang Anda beli.
                                </p>
                                @foreach ($order->items as $item)
                                    @php $reviewed = $item->product?->reviews->contains('user_id', auth()->id()); @endphp
                                    @if ($item->product)
                                        <div class="flex flex-wrap items-center justify-between gap-3 py-2">
                                            <span class="font-body-sm text-body-sm text-on-surface">{{ $item->product_name }}</span>
                                            @if ($reviewed)
                                                <span class="badge bg-tertiary-fixed text-on-tertiary-fixed">Sudah Diulas</span>
                                            @else
                                                <a href="{{ route('produk.show', $item->product->slug) }}#ulasan"
                                                   class="px-4 py-2 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                                                    Tulis Ulasan
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Alamat --}}
                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Alamat Pengiriman</h2>
                        <address class="not-italic">
                            <p class="font-label-lg text-label-lg font-semibold text-on-surface">{{ $order->customer_name }}</p>
                            <p class="font-body-sm text-body-sm text-outline mt-1">{{ $order->street }}</p>
                            <p class="font-body-sm text-body-sm text-outline">
                                {{ collect([$order->district, $order->city, $order->province, $order->postal_code])->filter()->implode(', ') }}
                            </p>
                            <p class="font-body-sm text-body-sm text-outline mt-1">{{ $order->phone }}</p>
                            <p class="font-body-sm text-body-sm text-outline">{{ $order->email }}</p>
                        </address>
                    </div>
                </div>

                {{-- Ringkasan & pembayaran --}}
                <aside class="lg:col-span-1 space-y-6">
                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Ringkasan</h2>

                        <dl class="space-y-2.5">
                            <div class="flex justify-between font-body-sm text-body-sm">
                                <dt class="text-outline">Subtotal</dt>
                                <dd class="text-on-surface font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                            </div>
                            @if ($order->discount > 0)
                                <div class="flex justify-between font-body-sm text-body-sm">
                                    <dt class="text-primary font-semibold">Diskon {{ $order->coupon_code }}</dt>
                                    <dd class="text-primary font-semibold">- Rp {{ number_format($order->discount, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                            <div class="flex justify-between font-body-sm text-body-sm">
                                <dt class="text-outline">Ongkos kirim</dt>
                                <dd class="text-on-surface font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</dd>
                            </div>
                            @if ($order->tax > 0)
                                <div class="flex justify-between font-body-sm text-body-sm">
                                    <dt class="text-outline">Pajak</dt>
                                    <dd class="text-on-surface font-semibold">Rp {{ number_format($order->tax, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                        </dl>

                        <div class="flex justify-between items-baseline mt-5 pt-4 border-t border-surface-container-low">
                            <span class="font-label-lg text-label-lg font-semibold text-on-surface">Total</span>
                            <span class="font-headline-sm text-headline-sm text-primary font-bold">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <p class="font-body-sm text-body-sm text-outline mt-3">
                            Metode pengiriman:
                            {{ config('petshop.shipping')[$order->shipping_method]['label'] ?? $order->shipping_method }}
                        </p>
                    </div>

                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Pembayaran</h2>

                        <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                            {{ $order->payment?->methodLabel() ?? config('petshop.payment_methods')[$order->payment_method] }}
                        </p>
                        <p class="font-body-sm text-body-sm text-outline mt-1">
                            {{ $order->payment?->isPaid() ? 'Pembayaran diterima' : 'Menunggu pembayaran' }}
                        </p>

                        @if ($order->payment?->reference)
                            <p class="font-body-sm text-body-sm text-outline mt-2">
                                Referensi: <span class="font-semibold">{{ $order->payment->reference }}</span>
                            </p>
                        @endif

                        @if ($order->payment?->payload['instructions'] ?? null)
                            <div class="mt-4 p-4 rounded-xl bg-surface-container-low">
                                <p class="font-label-md text-label-md font-bold text-on-surface mb-1.5">
                                    {{ $order->payment->payload['instructions']['title'] }}
                                </p>
                                @foreach ($order->payment->payload['instructions']['lines'] ?? [] as $line)
                                    <p class="font-body-sm text-body-sm text-outline">{{ $line }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if ($order->status === 'pending')
                            <form action="{{ route('pesanan.pay', $order) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                                    Saya Sudah Bayar
                                </button>
                            </form>
                        @endif

                        @if ($order->isCancellable())
                            <form action="{{ route('pesanan.cancel', $order) }}" method="POST" class="mt-2"
                                  onsubmit="return confirm('Batalkan pesanan {{ $order->order_number }}?')">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl bg-surface-container text-error font-label-lg text-label-lg font-bold hover:bg-error-container transition-colors">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection