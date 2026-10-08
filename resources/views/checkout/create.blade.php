@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="[
                'Keranjang' => route('keranjang.index'),
                'Checkout' => null,
            ]" />

            <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mb-2">
                Checkout
            </h1>
            <p class="font-body-sm text-body-sm text-outline mb-8">
                Harga final dihitung ulang oleh server dan tidak dapat diubah dari sisi browser.
            </p>

            <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf

                <div class="lg:col-span-2 space-y-6">
                    {{-- Data pengiriman --}}
                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">1</span>
                            Data Pengiriman
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form-field name="name" label="Nama Lengkap" required
                                          :value="$user?->name ?? old('name')" />
                            <x-form-field name="email" label="Email" type="email" required
                                          :value="$user?->email ?? old('email')" />
                            <x-form-field name="phone" label="Nomor Telepon" required
                                          :value="$user?->phone ?? old('phone')" placeholder="08xxxxxxxxxx" />
                            <x-form-field name="whatsapp" label="Nomor WhatsApp"
                                          :value="$user?->phone ?? old('whatsapp')" placeholder="Opsional" />

                            <div class="sm:col-span-2">
                                <x-form-field name="street" label="Alamat Lengkap" type="textarea" required
                                              :value="old('street')"
                                              placeholder="Nama jalan, nomor rumah, RT/RW, patokan" />
                            </div>

                            <x-form-field name="province" label="Provinsi" required
                                          :value="old('province')" placeholder="Contoh: Jawa Barat" />
                            <x-form-field name="city" label="Kota/Kabupaten" required
                                          :value="old('city')" placeholder="Contoh: Bandung" />
                            <x-form-field name="district" label="Kecamatan"
                                          :value="old('district')" placeholder="Contoh: Coblong" />
                            <x-form-field name="postal_code" label="Kode Pos" required
                                          :value="old('postal_code')" placeholder="40132" />
                        </div>
                    </div>

                    {{-- Pengiriman --}}
                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">2</span>
                            Metode Pengiriman
                        </h2>

                        <div class="space-y-3">
                            @foreach ($shippingMethods as $key => $method)
                                <label @class([
                                    'flex items-center gap-4 p-4 rounded-xl border cursor-pointer transition-colors',
                                    'border-primary bg-primary-fixed/20' => old('shipping_method', 'reguler') === $key,
                                    'border-surface-outline-app hover:bg-surface-container-low' => old('shipping_method', 'reguler') !== $key,
                                ])>
                                    <input type="radio" name="shipping_method" value="{{ $key }}"
                                           class="w-5 h-5 accent-primary" data-live-shipping
                                           @checked(old('shipping_method', 'reguler') === $key)>

                                    <span class="material-symbols-outlined text-[22px] text-primary">local_shipping</span>

                                    <span class="flex-1">
                                        <span class="font-label-lg text-label-lg font-bold text-on-surface block">{{ $method['label'] }}</span>
                                        <span class="font-body-sm text-body-sm text-outline">Estimasi {{ $method['estimate'] }}</span>
                                    </span>

                                    <span class="font-title-md text-title-md font-bold text-primary">
                                        Rp {{ number_format($method['cost'], 0, ',', '.') }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="surface-card p-6">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">3</span>
                            Metode Pembayaran
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($paymentMethods as $key => $label)
                                @php
                                    $icons = [
                                        'bank_transfer' => 'account_balance',
                                        'qris' => 'qr_code',
                                        'ewallet' => 'smartphone',
                                        'cod' => 'payments',
                                    ];
                                @endphp
                                <label @class([
                                    'flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-colors',
                                    'border-primary bg-primary-fixed/20' => old('payment_method', 'bank_transfer') === $key,
                                    'border-surface-outline-app hover:bg-surface-container-low' => old('payment_method', 'bank_transfer') !== $key,
                                ])>
                                    <input type="radio" name="payment_method" value="{{ $key }}"
                                           class="w-5 h-5 accent-primary"
                                           @checked(old('payment_method', 'bank_transfer') === $key)>
                                    <span class="material-symbols-outlined text-[22px] text-primary">{{ $icons[$key] ?? 'payments' }}</span>
                                    <span class="font-label-lg text-label-lg font-semibold text-on-surface">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <x-form-field name="notes" label="Catatan untuk Penjual" type="textarea" colspan="mt-5"
                                      :value="old('notes')"
                                      placeholder="Contoh: tolong dibungkus rapi, titip di pos satpam" />
                    </div>
                </div>

                {{-- Ringkasan --}}
                <aside class="lg:col-span-1">
                    <div class="surface-card p-6 lg:sticky lg:top-24">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Ringkasan Pesanan</h2>

                        <ul class="space-y-3 pb-5 mb-5 border-b border-surface-container-low max-h-64 overflow-y-auto">
                            @foreach ($items as $item)
                                <li class="flex items-center gap-3">
                                    <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}"
                                         class="w-12 h-12 rounded-lg object-cover bg-surface-container-low shrink-0" loading="lazy">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-body-sm text-body-sm text-on-surface font-semibold line-clamp-1">
                                            {{ $item->product->name }}
                                        </p>
                                        <p class="font-label-md text-label-md text-outline">
                                            {{ $item->quantity }} × Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <span class="font-label-md text-label-md font-bold text-on-surface shrink-0">
                                        Rp {{ number_format($item->product->final_price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Angka ini di-update via AJAX, tetapi nilainya tetap dihitung server --}}
                        <dl class="space-y-2.5" id="checkout-totals">
                            <div class="flex justify-between font-body-sm text-body-sm">
                                <dt class="text-outline">Subtotal</dt>
                                <dd class="text-on-surface font-semibold" data-total="subtotal">Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between font-body-sm text-body-sm">
                                <dt class="text-outline">Ongkos kirim</dt>
                                <dd class="text-surface-tint font-semibold" data-total="shipping_cost">Rp {{ number_format($totals['shipping_cost'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between font-body-sm text-body-sm">
                                <dt class="text-outline">Pajak</dt>
                                <dd class="text-surface-tint font-semibold" data-total="tax">Rp {{ number_format($totals['tax'], 0, ',', '.') }}</dd>
                            </div>
                        </dl>

                        <div class="flex justify-between items-baseline mt-5 pt-4 border-t border-surface-container-low">
                            <span class="font-label-lg text-label-lg font-semibold text-on-surface">Total Bayar</span>
                            <span class="font-headline-sm text-headline-sm text-primary font-bold" data-total="total">
                                Rp {{ number_format($totals['total'], 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" class="mt-6 w-full py-4 rounded-xl bg-secondary text-on-primary font-headline-sm text-headline-sm font-bold hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 shadow-md">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                            Buat Pesanan
                        </button>

                        <p class="font-body-sm text-body-sm text-outline text-center mt-4 flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">verified_user</span>
                            Transaksi Anda terenkripsi dan aman
                        </p>
                    </div>
                </aside>
            </form>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    // Perbarui ongkir saat metode pengiriman berubah (hanya tampilan;
    // server tetap menghitung ulang sendiri).
    const form = document.querySelector('form[action*="checkout"]');
    const totalsEl = document.getElementById('checkout-totals');

    const formatter = new Intl.NumberFormat('id-ID');

    document.querySelectorAll('[data-live-shipping]').forEach((radio) => {
        radio.addEventListener('change', async () => {
            if (!totalsEl) return;

            try {
                const { data } = await window.axios.get('/checkout/ringkasan', {
                    params: { shipping: radio.value },
                });

                const t = data.totals;
                const set = (key, value) => {
                    const el = totalsEl.parentElement.querySelector(`[data-total="${key}"]`);
                    if (el) el.textContent = 'Rp ' + formatter.format(value);
                };

                set('subtotal', t.subtotal);
                set('shipping_cost', t.shipping_cost);
                set('tax', t.tax);
                set('total', t.total);
            } catch (e) {
                console.warn('Gagal memperbarui ongkir');
            }
        });
    });
</script>
@endpush