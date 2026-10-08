<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:pending,published,hidden'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'q' => ['nullable', 'string', 'max:100'],
        ], [], ['rating' => 'rating']);

        $reviews = Review::query()
            ->with(['user', 'product', 'order'])
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['rating'] ?? null, fn ($q, $rating) => $q->where('rating', $rating))
            ->when($validated['q'] ?? null, fn ($q, $keyword) => $q->where(function ($sub) use ($keyword) {
                $sub->where('comment', 'ilike', '%'.$keyword.'%')
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'ilike', '%'.$keyword.'%'))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'ilike', '%'.$keyword.'%'));
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'filters' => $validated,
            'counts' => [
                'all' => Review::count(),
                'published' => Review::where('status', 'published')->count(),
                'pending' => Review::where('status', 'pending')->count(),
                'hidden' => Review::where('status', 'hidden')->count(),
            ],
        ]);
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,published,hidden'],
        ], [], ['status' => 'status']);

        $review->update(['status' => $validated['status']]);

        $this->recalculate($review->product_id);

        return back()->with('success', 'Status ulasan diperbarui.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $productId = $review->product_id;
        $review->delete();

        $this->recalculate($productId);

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    private function recalculate(?int $productId): void
    {
        if (! $productId) {
            return;
        }

        $product = \App\Models\Product::find($productId);

        if (! $product) {
            return;
        }

        $stats = Review::query()
            ->where('product_id', $productId)
            ->published()
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as total')
            ->first();

        $product->update([
            'rating_average' => round((float) $stats->avg_rating, 2),
            'rating_count' => (int) $stats->total,
        ]);
    }
}