<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(private readonly CartTotalsService $totals) {}

    /**
     * User login -> cart disimpan permanen di database (tabel carts).
     * Guest -> cart di-session, lalu dimigrasi ke database saat login.
     */
    public function current(?Authenticatable $user = null): ?Cart
    {
        $user ??= auth()->user();

        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->getAuthIdentifier()]);
        }

        $cartId = Session::get('cart_id');

        if (! $cartId) {
            return null;
        }

        return Cart::with('items.product.primaryImage')
            ->where('session_id', Session::getId())
            ->whereKey($cartId)
            ->first();
    }

    public function items(?Authenticatable $user = null)
    {
        $cart = $this->current($user);

        if (! $cart) {
            return collect();
        }

        return $cart->items()->with('product.primaryImage', 'product.category', 'product.brand')->get();
    }

    public function addItem(Product $product, int $quantity = 1, ?Authenticatable $user = null): CartItem
    {
        $cart = $this->current($user);

        if (! $cart) {
            $cart = Cart::create(['session_id' => Session::getId()]);
            Session::put('cart_id', $cart->id);
        }

        $item = $cart->items()->where('product_id', $product->id)->first();

        $newQty = $quantity + ($item?->quantity ?? 0);
        $newQty = min($newQty, max($product->stock, 0));

        if ($item) {
            $item->update([
                'quantity' => $newQty,
                'price_snapshot' => $product->final_price,
            ]);

            return $item->refresh();
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => max(1, min($quantity, max($product->stock, 1))),
            'price_snapshot' => $product->final_price,
        ]);
    }

    public function updateQuantity(int $itemId, int $quantity, ?Authenticatable $user = null): ?CartItem
    {
        $item = CartItem::with('product')->find($itemId);

        if (! $item || ! $this->owns($item, $user)) {
            return null;
        }

        if ($quantity < 1) {
            $item->delete();

            return null;
        }

        $quantity = min($quantity, max($item->product->stock, 0));

        if ($quantity < 1) {
            $item->delete();

            return null;
        }

        $item->update(['quantity' => $quantity]);

        return $item->refresh();
    }

    public function removeItem(int $itemId, ?Authenticatable $user = null): bool
    {
        $item = CartItem::find($itemId);

        if (! $item || ! $this->owns($item, $user)) {
            return false;
        }

        return (bool) $item->delete();
    }

    public function clear(?Authenticatable $user = null): void
    {
        $cart = $this->current($user);

        $cart?->items()->delete();
    }

    public function count(?Authenticatable $user = null): int
    {
        return (int) $this->items($user)->sum('quantity');
    }

    /**
     * Dipanggil setelah login: session cart digabung ke cart user.
     */
    public function mergeGuestCart(Authenticatable $user): void
    {
        $cartId = Session::get('cart_id');

        if (! $cartId) {
            return;
        }

        $guestCart = Cart::with('items')->where('session_id', Session::getId())->find($cartId);

        if ($guestCart) {
            foreach ($guestCart->items as $item) {
                $this->addItem(Product::findOrFail($item->product_id), $item->quantity, $user);
            }

            $guestCart->delete();
        }

        Session::forget('cart_id');
    }

    /**
     * Mencegah user mengutak-atik cart milik orang lain.
     */
    private function owns(CartItem $item, ?Authenticatable $user = null): bool
    {
        $user ??= auth()->user();

        if ($user) {
            return $item->cart?->user_id === $user->getAuthIdentifier();
        }

        return $item->cart?->session_id === Session::getId();
    }
}