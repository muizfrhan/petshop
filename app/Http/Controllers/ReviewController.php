<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $product = Product::where('status', 'active')->findOrFail($request->integer('product_id'));
        $user = $request->user();

        /*
         | Aturan bisnis (bukan sekadar validasi form):
         | Ulasan hanya boleh dibuat jika user punya order COMPLETED
         | yang benar-benar memuat produk ini.
         */
        $order = Order::query()
            ->where('user_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->latest('completed_at')
            ->first();

        if (! $order) {
            return back()->with('error', 'Anda hanya bisa ulasan produk yang sudah Anda beli dan pesanannya selesai.');
        }

        $already = Review::withTrashed()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Anda sudah ulasan produk ini.');
        }

        DB::transaction(function () use ($request, $user, $product, $order) {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'order_id' => $order->id,
                'rating' => $request->integer('rating'),
                'comment' => $request->input('comment'),
                'status' => Review::STATUS_PUBLISHED,
            ]);

            $this->recalculateProductRating($product);
        });

        return back()->with('success', 'Terima kasih! Ulasan Anda sudah kami-published.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        abort_if($review->user_id !== $request->user()->id && ! $request->user()->isAdmin(), 403);

        DB::transaction(function () use ($review) {
            $product = $review->product;
            $review->delete();

            if ($product) {
                $this->recalculateProductRating($product);
            }
        });

        return back()->with('success', 'Ulasan telah dihapus.');
    }

    /** Rating produk = rata-rata ulasan yang published. */
    private function recalculateProductRating(Product $product): void
    {
        $stats = Review::query()
            ->where('product_id', $product->id)
            ->published()
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as total')
            ->first();

        $product->update([
            'rating_average' => round((float) $stats->avg_rating, 2),
            'rating_count' => (int) $stats->total,
        ]);
    }
}