<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CartTotalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly CartTotalsService $totals,
    ) {}

    public function index(Request $request)
    {
        $items = $this->cart->items();
        $coupon = null;

        if ($code = $request->session()->get('coupon_code')) {
            $coupon = Coupon::active()->whereRaw('UPPER(code) = ?', [strtoupper($code)])->first();
        }

        return view('cart.index', [
            'items' => $items,
            'totals' => $this->totals->calculate($items, $request->input('shipping', 'reguler'), $coupon),
            'coupon' => $coupon,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ], [], ['product_id' => 'produk']);

        $product = Product::where('status', 'active')->findOrFail($validated['product_id']);

        if ($product->isOutOfStock()) {
            return $this->respondError($request, "Stok {$product->name} habis.", 422);
        }

        $this->cart->addItem($product, (int) ($validated['quantity'] ?? 1));

        return $this->respondSuccess($request, "{$product->name} ditambahkan ke keranjang!", $product->name);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $item = $this->cart->updateQuantity($id, (int) $validated['quantity']);

        if (! $item) {
            return $this->respondError($request, 'Item keranjang tidak ditemukan.', 404);
        }

        return $this->respondSuccess($request, 'Keranjang diperbarui.');
    }

    public function destroy(Request $request, int $id)
    {
        $removed = $this->cart->removeItem($id);

        if (! $removed) {
            return $this->respondError($request, 'Item keranjang tidak ditemukan.', 404);
        }

        return $this->respondSuccess($request, 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request)
    {
        $this->cart->clear();
        $request->session()->forget('coupon_code');

        return $this->respondSuccess($request, 'Keranjang berhasil dikosongkan.');
    }

    /** Buy now: langsung ke checkout dengan item tersebut. */
    public function buyNow(Request $request, int $id)
    {
        $item = CartItem::with('product')->find($id);

        if (! $item) {
            return back()->with('error', 'Item keranjang tidak ditemukan.');
        }

        $this->cart->clear();
        $this->cart->addItem($item->product, $item->quantity);

        return redirect()->route('checkout.create');
    }

    /** Buy now dari halaman detail produk: keranjang dikosongkan dulu. */
    public function buyNowFromProduct(Request $request, Product $product)
    {
        if ($product->isOutOfStock() || $product->status !== 'active') {
            return redirect()->route('produk.show', $product->slug)
                ->with('error', 'Produk ini sedang tidak tersedia.');
        }

        $quantity = max(1, min((int) $request->query('qty', 1), $product->stock));

        $this->cart->clear();
        $this->cart->addItem($product, $quantity);

        return redirect()->route('checkout.create');
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:40'],
        ], [], ['coupon_code' => 'kode kupon']);

        $items = $this->cart->items();
        $subtotal = $this->totals->subtotal($items);

        $coupon = Coupon::active()
            ->whereRaw('UPPER(code) = ?', [strtoupper($validated['coupon_code'])])
            ->first();

        if (! $coupon) {
            return back()->with('error', 'Kode kupon tidak valid atau sudah berakhir.');
        }

        if (! $coupon->isValidFor($subtotal)) {
            return back()->with('error', 'Kode kupon tidak dapat dipakai untuk pesanan ini.');
        }

        $request->session()->put('coupon_code', $coupon->code);

        return back()->with('success', "Kupon {$coupon->code} berhasil dipakai.");
    }

    /**
     * Dipakai AJAX setelah add/update/remove agar drawer tetap sinkron.
     */
    public function summary(Request $request)
    {
        $items = $this->cart->items();
        $coupon = $request->session()->get('coupon_code')
            ? Coupon::active()->whereRaw('UPPER(code) = ?', [strtoupper($request->session()->get('coupon_code'))])->first()
            : null;

        $totals = $this->totals->calculate($items, $request->input('shipping', 'reguler'), $coupon);
        $totals['count'] = (int) $items->sum('quantity');

        return response()->json([
            'count' => $totals['count'],
            'totals' => $totals,
            'html' => view('layouts.partials.cart-items', ['cartItems' => $items])->render(),
            'summaryHtml' => view('layouts.partials.cart-summary', ['totals' => $totals])->render(),
        ]);
    }

    private function respondSuccess(Request $request, string $message, ?string $productName = null)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'product_name' => $productName,
                'count' => $this->cart->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    private function respondError(Request $request, string $message, int $status)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $status);
        }

        return back()->with('error', $message);
    }
}