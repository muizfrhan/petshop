<?php

namespace App\Services;

use App\Exceptions\StockUnavailableException;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(private readonly ProductImageService $images) {}

    public function create(array $data, ?User $actor = null): Product
    {
        $data['slug'] = $this->uniqueSlug($data['name'], $data['slug'] ?? null);
        $data['sku'] = $this->uniqueSku($data['sku'] ?? null, $data['name']);

        return DB::transaction(function () use ($data, $actor) {
            $product = Product::create($data);

            $this->images->deleteByIds($product, $data['delete_images'] ?? []);
            $this->images->syncUploaded($product, $data['images'] ?? []);
            $this->images->syncExisting($product, $data['existing_images'] ?? []);

            return $product->refresh();
        });
    }

    public function update(Product $product, array $data): Product
    {
        $data['slug'] = $this->uniqueSlug($data['name'] ?? $product->name, $data['slug'] ?? null, $product->id);
        $data['sku'] = $this->uniqueSku($data['sku'] ?? null, $data['name'] ?? $product->name, $product->id);

        return DB::transaction(function () use ($product, $data) {
            $product->update($data);

            $this->images->syncUploaded($product, $data['images'] ?? []);
            $this->images->syncExisting($product, $data['existing_images'] ?? []);
            $this->images->removeMissing($product, $data['existing_images'] ?? []);

            return $product->refresh();
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            $product->images()->delete();
            $product->delete(); // soft delete
        });
    }

    /**
     * @return Collection<int, Product>
     */
    public function activeProducts()
    {
        return Product::query()
            ->active()
            ->with('primaryImage', 'category', 'brand')
            ->latest();
    }

    public function decrementStock(Product $product, int $quantity): void
    {
        // lockForUpdate mencegah dua checkoutParalelCells stok yang sama
        $locked = Product::whereKey($product->id)->lockForUpdate()->first();

        if (! $locked || $locked->stock < $quantity) {
            throw new StockUnavailableException(
                "Stok {$locked?->name} tidak mencukupi.",
            );
        }

        $locked->decrement('stock', $quantity);
        $locked->increment('sold_count', $quantity);
    }

    public function uniqueSlug(string $name, ?string $slug = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name) ?: 'produk';
        $candidate = $base;
        $i = 2;

        while (
            Product::withTrashed()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $candidate = "{$base}-{$i}";
            $i++;
        }

        return $candidate;
    }

    public function uniqueSku(?string $sku, string $name, ?int $ignoreId = null): string
    {
        $base = strtoupper(trim((string) $sku))
            ?: strtoupper(Str::slug(Str::limit($name, 12, '')));
        $candidate = $base;
        $i = 2;

        while (
            Product::withTrashed()
                ->where('sku', $candidate)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $candidate = "{$base}-{$i}";
            $i++;
        }

        return $candidate;
    }
}