<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Services\CartTotalsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bagikan badge keranjang/wishlist ke SEMUA halaman lewat satu partial.
        View::composer('layouts.partials.navbar', function ($view) {
            $user = Auth::user();

            if ($user) {
                $cartItems = CartItem::query()
                    ->whereHas('cart', fn ($q) => $q->where('user_id', $user->id))
                    ->with('product')
                    ->get();

                $view->with([
                    'cartCount' => (int) $cartItems->sum('quantity'),
                    'wishlistCount' => $user->wishlists()->count(),
                    'cartItems' => $cartItems,
                    'cartTotals' => $this->drawerTotals($cartItems),
                ]);

                return;
            }

            $cartId = session('cart_id');

            $cartItems = $cartId
                ? CartItem::query()
                    ->whereHas('cart', fn ($q) => $q->where('session_id', session()->getId())->whereKey($cartId))
                    ->with('product')
                    ->get()
                : collect();

            $view->with([
                'cartCount' => (int) $cartItems->sum('quantity'),
                'wishlistCount' => 0,
                'cartItems' => $cartItems,
                'cartTotals' => $this->drawerTotals($cartItems),
            ]);
        });
    }

    /**
     * @param  Collection<int, CartItem>  $items
     * @return array<string, mixed>|null
     */
    private function drawerTotals($items)
    {
        if ($items->isEmpty()) {
            return null;
        }

        $totals = app(CartTotalsService::class)->calculate($items);
        $totals['count'] = (int) $items->sum('quantity');

        return $totals;
    }
}
