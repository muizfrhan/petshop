<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function __construct(private readonly ProductService $products) {}

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'stok' => ['nullable', 'string', 'in:habis,menipis,tersedia'],
            'per_page' => ['nullable', 'integer', 'between:10,100'],
        ], [], ['kategori' => 'kategori', 'brand' => 'brand', 'stok' => 'stok']);

        $threshold = config('petshop.low_stock_threshold', 10);

        $products = Product::query()
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
            ->when($validated['status'] ?? null, fn (Builder $q, $status) => $q->where('status', $status))
            ->when(($validated['stok'] ?? null) === 'habis', fn (Builder $q) => $q->where('stock', '<=', 0))
            ->when(($validated['stok'] ?? null) === 'menipis', fn (Builder $q) => $q->where('stock', '>', 0)->where('stock', '<=', $threshold))
            ->when(($validated['stok'] ?? null) === 'tersedia', fn (Builder $q) => $q->where('stock', '>', $threshold))
            ->latest()
            ->paginate((int) ($validated['per_page'] ?? 15))
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::active()->ordered()->get(),
            'brands' => Brand::active()->ordered()->get(),
            'filters' => $validated,
            'lowStockThreshold' => $threshold,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['status' => 'active', 'stock' => 0, 'price' => 0]),
            'categories' => Category::active()->ordered()->get(),
            'brands' => Brand::active()->ordered()->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $product = $this->products->create($validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Produk {$product->name} berhasil ditambahkan.");
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'brand', 'primaryImage', 'allReviews.user', 'orderItems']);

        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product): View
    {
        $product->load('primaryImage');

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::active()->ordered()->get(),
            'brands' => Brand::active()->ordered()->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->rules($product));

        $this->products->update($product, $validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Produk {$product->name} berhasil diperbarui.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->products->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil dihapus.");
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?Product $product = null): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'name' => ['required', 'string', 'min:3', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash'],
            'sku' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:10000'],
            'specification' => ['nullable', 'string', 'max:10000'],

            'price' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'weight' => ['nullable', 'integer', 'min:0', 'max:1000000'],

            'status' => ['required', 'string', 'in:active,inactive'],
            'featured' => ['nullable', 'boolean'],

            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['nullable', 'array'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
        ];
    }
}