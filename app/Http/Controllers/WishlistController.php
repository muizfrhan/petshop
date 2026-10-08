<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(Request $request)
    {
        $products = $request->user()
            ->wishlists()
            ->with(['product.primaryImage', 'product.category'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', [
            'wishlists' => $products,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ], [], ['product_id' => 'produk']);

        $product = Product::where('status', 'active')->findOrFail($validated['product_id']);

        $exists = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            Wishlist::where('user_id', $request->user()->id)->where('product_id', $product->id)->delete();

            return $this->respond($request, "{$product->name} dihapus dari favorit.", false, [
                'product_id' => $product->id,
                'count' => $request->user()->wishlists()->count(),
            ]);
        }

        Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return $this->respond($request, "{$product->name} disimpan ke favorit!", true, [
            'product_id' => $product->id,
            'count' => $request->user()->wishlists()->count(),
        ]);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return $this->respond($request, "{$product->name} dihapus dari favorit.", false, [
            'product_id' => $product->id,
            'count' => $request->user()->wishlists()->count(),
        ]);
    }

    /** Pindahkan seluruh wishlist ke keranjang. */
    public function moveToCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ], [], ['product_id' => 'produk']);

        $query = $request->user()->wishlists()->with('product');

        if (! empty($validated['product_id'])) {
            $query->where('product_id', $validated['product_id']);
        }

        $wishlists = $query->get();
        $added = 0;
        $skipped = [];

        foreach ($wishlists as $wishlist) {
            $product = $wishlist->product;

            if (! $product || ! $product->isActive() || $product->isOutOfStock()) {
                $skipped[] = $product?->name ?? 'produk';

                continue;
            }

            $this->cart->addItem($product, 1);
            $added++;

            $wishlist->delete();
        }

        if ($added === 0) {
            return response()->json([
                'message' => 'Tidak ada produk di wishlist yang bisa ditambahkan.',
                'count' => 0,
                'skipped' => $skipped,
            ]);
        }

        return response()->json([
            'message' => "{$added} produk dipindahkan ke keranjang.",
            'count' => $added,
            'skipped' => $skipped,
        ]);
    }

    private function respond(Request $request, string $message, bool $active, array $extra)
    {
        if ($request->expectsJson()) {
            return response()->json($extra + ['message' => $message, 'active' => $active]);
        }

        return back()->with('success', $message);
    }
}