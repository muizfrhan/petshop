<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\PaymentConfirmedNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Throwable;

class CheckoutService
{
    public function __construct(
        private readonly CartTotalsService $totals,
        private readonly PaymentService $payment,
    ) {}

    /**
     * Buat order dari cart. Semua harga & stok divalidasi ulang di backend.
     *
     * @param  array<string, mixed>  $data
     */
    public function placeOrder(Cart $cart, array $data, ?Authenticatable $user = null): Order
    {
        $items = $cart->items()->with('product.primaryImage')->get()->filter(
            fn ($item) => $item->product !== null,
        );

        if ($items->isEmpty()) {
            throw new \RuntimeException('Keranjang belanja Anda masih kosong.');
        }

        $this->assertStock($items);

        $coupon = null;
        if (! empty($data['coupon_code'])) {
            $coupon = Coupon::active()->whereRaw('UPPER(code) = ?', [strtoupper($data['coupon_code'])])->first();
        }

        $totals = $this->totals->calculate($items, $data['shipping_method'] ?? 'reguler', $coupon);

        return DB::transaction(function () use ($cart, $data, $user, $items, $coupon, $totals) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user?->getAuthIdentifier(),
                'coupon_id' => $coupon?->id,
                'status' => Order::STATUS_PENDING,
                'customer_name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp'] ?? $data['phone'],
                'street' => $data['street'],
                'province' => $data['province'],
                'city' => $data['city'],
                'district' => $data['district'] ?? null,
                'postal_code' => $data['postal_code'],
                'shipping_method' => $data['shipping_method'] ?? 'reguler',
                'shipping_address' => collect([
                    $data['street'],
                    $data['district'] ?? null,
                    $data['city'],
                    $data['province'],
                    $data['postal_code'],
                ])->filter()->implode(', '),
                'notes' => $data['notes'] ?? null,
                ...$totals,
                'coupon_code' => $coupon?->code,
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($items as $item) {
                $product = $item->product;
                $price = $product->final_price;
                $image = $product->primaryImage->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $image?->path,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                ]);

                // potong stok dengan row lock supaya aman dari checkout paralel
                $locked = \App\Models\Product::whereKey($product->id)->lockForUpdate()->first();

                if (! $locked || $locked->stock < $item->quantity) {
                    throw new \App\Exceptions\StockUnavailableException(
                        "Stok {$product->name} baru saja habis. Mohon perbarui keranjang Anda.",
                    );
                }

                $locked->decrement('stock', $item->quantity);
                $locked->increment('sold_count', $item->quantity);
            }

            $this->payment->createForOrder($order, $data['payment_method']);

            if ($coupon) {
                $coupon->increment('used_count');

                $coupon->usages()->create([
                    'user_id' => $user?->getAuthIdentifier(),
                    'order_id' => $order->id,
                    'discount_amount' => $totals['discount'],
                ]);
            }

            $cart->items()->delete();

            return $order->refresh();
        });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\CartItem>  $items
     */
    private function assertStock($items): void
    {
        foreach ($items as $item) {
            $product = $item->product;

            if ($product->isOutOfStock()) {
                throw new \App\Exceptions\StockUnavailableException("Stok {$product->name} habis.");
            }

            if ($item->quantity > $product->stock) {
                throw new \App\Exceptions\StockUnavailableException(
                    "Stok {$product->name} hanya tersisa {$product->stock} unit.",
                );
            }
        }
    }

    public function generateOrderNumber(): string
    {
        do {
            $number = 'PCH-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}