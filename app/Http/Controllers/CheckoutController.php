<?php

namespace App\Http\Controllers;

use App\Exceptions\StockUnavailableException;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\CartTotalsService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly CartTotalsService $totals,
        private readonly CheckoutService $checkout,
    ) {}

    public function create()
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('warning', 'Keranjangmu masih kosong. Pilih produk dulu, ya.');
        }

        return view('checkout.create', [
            'items' => $items,
            'totals' => $this->totals->calculate($items, request('shipping', 'reguler')),
            'shippingMethods' => config('petshop.shipping'),
            'paymentMethods' => config('petshop.payment_methods'),
            'user' => auth()->user(),
        ]);
    }

    public function summary()
    {
        $items = $this->cart->items();

        $totals = $this->totals->calculate($items, request('shipping', 'reguler'));
        $totals['count'] = (int) $items->sum('quantity');

        return response()->json([
            'count' => $totals['count'],
            'totals' => $totals,
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cart->current();

        if (! $cart || $cart->items()->count() === 0) {
            return redirect()->route('keranjang.index')
                ->with('warning', 'Keranjangmu masih kosong.');
        }

        try {
            // Harga & stok divalidasi ULANG di dalam service (bukan dari form).
            $order = $this->checkout->placeOrder($cart, $request->validated(), $request->user());
        } catch (StockUnavailableException $e) {
            return redirect()->route('keranjang.index')->with('error', $e->getMessage());
        } catch (\RuntimeException $e) {
            return redirect()->route('keranjang.index')->with('error', $e->getMessage());
        }

        $request->session()->forget('coupon_code');

        if ($request->user()) {
            return redirect()->route('pesanan.show', $order)
                ->with('success', 'Pesanan '.$order->order_number.' berhasil dibuat.');
        }

        // Guest: simpan nomor pesanan agar bisa dilacak tanpa login
        $request->session()->put('guest_order', $order->order_number);

        return redirect()->route('pesanan.show', $order)
            ->with('success', 'Pesanan '.$order->order_number.' berhasil dibuat.');
    }
}