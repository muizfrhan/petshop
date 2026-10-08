<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'min' => ['nullable', 'integer', 'min:0'],
            'max' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'sort' => ['nullable', 'string', 'in:latest,price_asc,price_desc,popular,rating'],
            'per_page' => ['nullable', 'integer', 'between:6,48'],
        ], [], [
            'kategori' => 'kategori',
            'brand' => 'brand',
            'min' => 'harga minimum',
            'max' => 'harga maksimum',
            'rating' => 'rating',
            'sort' => 'urutan',
        ]);

        $sort = $validated['sort'] ?? 'latest';

        /*
         | Permintaan AJAX (live search navbar) hanya butuh data ringkas,
         | bukan seluruh markup halaman.
         */
        if ($request->expectsJson()) {
            $results = Product::query()
                ->active()
                ->with(['primaryImage'])
                ->search($validated['q'] ?? null)
                ->when($validated['kategori'] ?? null, fn (Builder $q, $slug) => $q->whereHas(
                    'category',
                    fn ($c) => $c->where('slug', $slug),
                ))
                ->when($validated['brand'] ?? null, fn (Builder $q, $slug) => $q->whereHas(
                    'brand',
                    fn ($b) => $b->where('slug', $slug),
                ))
                ->sort($sort)
                ->limit(5)
                ->get()
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => (int) $product->price,
                    'discount_price' => $product->discount_price ? (int) $product->discount_price : null,
                    'final_price' => $product->final_price,
                    'primary_image' => $product->primaryImage->map(fn ($img) => [
                        'path' => $img->path,
                        'url' => $img->url(),
                    ]),
                ]);

            return response()->json([
                'products' => $results,
                'total' => $results->count(),
            ]);
        }

        $perPage = (int) ($validated['per_page'] ?? 12);

        $products = Product::query()
            ->active()
            ->with(['primaryImage', 'category', 'brand'])
            ->search($validated['q'] ?? null)
            ->when($validated['kategori'] ?? null, fn (Builder $q, $slug) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $slug),
            ))
            ->when($validated['brand'] ?? null, fn (Builder $q, $slug) => $q->whereHas(
                'brand',
                fn ($b) => $b->where('slug', $slug),
            ))
            ->when($validated['min'] ?? null, fn (Builder $q, $min) => $q->where(function (Builder $sub) use ($min) {
                $sub->where('price', '>=', $min)->orWhere('discount_price', '>=', $min);
            }))
            ->when($validated['max'] ?? null, fn (Builder $q, $max) => $q->where(function (Builder $sub) use ($max) {
                $sub->where('price', '<=', $max)->orWhere('discount_price', '<=', $max);
            }))
            ->when($validated['rating'] ?? null, fn (Builder $q, $rating) => $q->where('rating_average', '>=', $rating))
            ->sort($sort)
            ->paginate($perPage)
            ->withQueryString();

        return view('shop.index', [
            'products' => $products,
            'categories' => Category::active()->ordered()->withCount('products')->get(),
            'brands' => Brand::active()->ordered()->withCount('products')->get(),
            'filters' => $validated,
            'search' => $validated['q'] ?? null,
            'wishlistIds' => $this->wishlistIds(),
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $product = Product::query()
            ->active()
            ->with([
                'primaryImage',
                'category',
                'brand',
                'reviews.user',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn (Builder $q) => $q->where('category_id', $product->category_id))
            ->with('primaryImage')
            ->take(4)
            ->get();

        return view('shop.show', [
            'product' => $product,
            'related' => $related,
            'inWishlist' => $request->user()
                ? $request->user()->wishlists()->where('product_id', $product->id)->exists()
                : false,
        ]);
    }

    public function category(Request $request, string $slug): View
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();

        $products = Product::query()
            ->active()
            ->where('category_id', $category->id)
            ->with(['primaryImage', 'brand'])
            ->sort($request->query('sort', 'latest'))
            ->paginate(12)
            ->withQueryString();

        return view('shop.category', [
            'category' => $category,
            'products' => $products,
            'wishlistIds' => $this->wishlistIds(),
        ]);
    }

    /** @return array<int, int> */
    private function wishlistIds(): array
    {
        $user = request()->user();

        if (! $user) {
            return [];
        }

        return $user->wishlists()->pluck('product_id')->all();
    }
}