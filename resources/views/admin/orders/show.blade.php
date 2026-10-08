@extends('layouts.admin')

@section('title', $order->order_number)
@section('subtitle', 'Detail pesanan · ' . $order->created_at->format('d F Y, H:i'))
@section('hideErrors', true)

@section('admin-content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Ringkasan & aksi --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6 pb-6 border-b border-surface-container-low">
                    <div>
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Status Pesanan</p>
                        <x-status-badge :status="$order->status" />
                    </div>

                    <div class="text-right">
                        <p class="font-label-caps text-label-caps text-outline uppercase mb-1">Total Bayar</p>
                        <p class="font-headline-sm text-headline-sm text-primary font-bold">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                @if (count($allowedTransitions) > 0)
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="form-label" for="status">Ubah Status</label>
                            <select id="status" name="status" class="form-input" required>
                                @foreach ($allowedTransitions as $status)
                                    <option value="{{ $status }}">
                                        {{ config('petshop.order_statuses')[$status] ?? $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label" for="payment_reference">
                                Referensi Pembayaran
                            </label>
                            <input type="text" id="payment_reference" name="payment_reference"
                                   value="{{ $order->payment?->reference }}"
                                   class="form-input"
                                   placeholder="Contoh: TRX-20250115-ABC">
                        </div>

                        <button type="submit"
                                class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Perbarui Status
                        </button>
                    </form>
                @else
                    <x-alert variant="success">
                        Pesanan ini sudah berstatus final ({{ $order->statusLabel() }}) dan tidak dapat diubah lagi.
                    </x-alert>
                @endif
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Produk Dipesan</h2>

                <ul class="space-y-4">
                    @foreach ($order->items as $item)
                        <li class="flex items-center gap-4">
                            <img src="{{ $item->product?->imageUrl() ?? asset('images/hero-card.jpg') }}"
                                 alt="{{ $item->product_name }}"
                                 class="w-14 h-14 rounded-xl object-cover bg-surface-container-low shrink-0" loading="lazy">
                            <div class="min-w-0 flex-1">
                                <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $item->product_name }}</p>
                                <p class="font-label-md text-label-md text-outline">
                                    {{ $item->product_sku }} · Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <span class="font-label-lg text-label-lg font-bold text-on-surface shrink-0">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <dl class="space-y-2.5 mt-6 pt-5 border-t border-surface-container-low">
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
                    <div class="flex justify-between font-label-lg text-label-lg pt-2 border-t border-surface-container-low">
                        <dt class="font-semibold text-on-surface">Total</dt>
                        <dd class="font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            @if ($order->reviews->isNotEmpty())
                <div class="surface-card p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Ulasan Produk</h2>
                    <ul class="space-y-3">
                        @foreach ($order->reviews as $review)
                            <li class="p-3 rounded-xl bg-surface-container-low">
                                <div class="flex items-center justify-between gap-3 mb-1.5">
                                    <span class="font-label-lg text-label-lg font-semibold text-on-surface">
                                        {{ $review->product?->name }}
                                    </span>
                                    <x-rating :rating="$review->rating" :show-count="false" />
                                </div>
                                <p class="font-body-sm text-body-sm text-outline">{{ $review->comment }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Pelanggan</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Nama</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Email</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">{{ $order->email }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Telepon</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">{{ $order->phone }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Akun</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">
                            {{ $order->user ? $order->user->name : 'Pesanan tamu' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-4">Alamat Pengiriman</h2>
                <address class="not-italic">
                    <p class="font-body-sm text-body-sm text-on-surface">{{ $order->street }}</p>
                    <p class="font-body-sm text-body-sm text-outline">
                        {{ collect([$order->district, $order->city, $order->province, $order->postal_code])->filter()->implode(', ') }}
                    </p>
                </address>

                <dl class="space-y-3 mt-5 pt-5 border-t border-surface-container-low">
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Pengiriman</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">
                            {{ config('petshop.shipping')[$order->shipping_method]['label'] ?? $order->shipping_method }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-label-caps text-label-caps text-outline uppercase">Metode Bayar</dt>
                        <dd class="font-body-sm text-body-sm text-on-surface">
                            {{ config('petshop.payment_methods')[$order->payment_method] ?? $order->payment_method }}
                        </dd>
                    </div>
                    @if ($order->paid_at)
                        <div>
                            <dt class="font-label-caps text-label-caps text-outline uppercase">Dibayar Pada</dt>
                            <dd class="font-body-sm text-body-sm text-on-surface">{{ $order->paid_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($order->notes)
                    <div class="mt-5 p-3 rounded-xl bg-surface-container-low">
                        <p class="font-label-md text-label-md font-bold text-on-surface mb-1">Catatan Pelanggan</p>
                        <p class="font-body-sm text-body-sm text-outline">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="w-full py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                Kembali ke Daftar Pesanan
            </a>
        </aside>
    </div>
@endsection