<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartTotalsService
{
    /**
     * Semua perhitungan uang terjadi di backend.
     * Harga produk TIDAK PERNAH diambil dari input form/frontend.
     */
    public function subtotal(Collection $items): int
    {
        return (int) $items->sum(function ($item) {
            /** @var Product $product */
            $product = $item->product;

            return $product?->final_price * (int) $item->quantity;
        });
    }

    public function shippingCost(string $method, int $subtotal): int
    {
        $methods = config('petshop.shipping');
        $method = array_key_exists($method, $methods) ? $method : 'reguler';

        return (int) ($methods[$method]['cost'] ?? 0);
    }

    public function tax(int $subtotal): int
    {
        // Toko belum menetapkan PPN -> 0, tapi strukturnya sudah disiapkan.
        return 0;
    }

    public function discountFor(?Coupon $coupon, int $subtotal): int
    {
        if (! $coupon) {
            return 0;
        }

        return $coupon->discountFor($subtotal);
    }

    /**
     * @return array{subtotal:int,discount:int,shipping_cost:int,tax:int,total:int}
     */
    public function calculate(Collection $items, string $shippingMethod = 'reguler', ?Coupon $coupon = null): array
    {
        $subtotal = $this->subtotal($items);
        $discount = $this->discountFor($coupon, $subtotal);

        // Diskon tidak boleh membuat subtotal minus.
        $discount = min($discount, $subtotal);

        $shipping = $this->shippingCost($shippingMethod, $subtotal);
        $tax = $this->tax($subtotal);

        // Ongkir gratis bila subtotal sudah habis setelah diskon.
        if ($subtotal - $discount <= 0) {
            $shipping = 0;
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_cost' => $shipping,
            'tax' => $tax,
            'total' => max(0, $subtotal - $discount) + $shipping + $tax,
        ];
    }
}