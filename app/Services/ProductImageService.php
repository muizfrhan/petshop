<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    private const DIR = 'products';

    private const RULES = [
        'jpg', 'jpeg', 'png', 'webp',
    ];

    /**
     * Simpan file gambar baru yang diunggah admin.
     *
     * @param  array<int, UploadedFile>  $files
     */
    public function storeUploaded(Product $product, array $files): void
    {
        $sort = (int) $product->images()->max('sort_order');
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || ! $this->isValid($file)) {
                continue;
            }

            $path = $file->store(self::DIR, 'public');

            $product->images()->create([
                'path' => $path,
                'alt' => $product->name,
                'is_primary' => ! $hasPrimary && $sort === 0,
                'sort_order' => ++$sort,
            ]);

            $hasPrimary = true;
        }
    }

    public function syncUploaded(Product $product, array $files): void
    {
        $files = array_values(array_filter(
            $files,
            fn ($f) => $f instanceof UploadedFile,
        ));

        if ($files !== []) {
            $this->storeUploaded($product, $files);
        }
    }

    /**
     * Update metadata gambar lama (primary / sort order).
     *
     * @param  array<int|string, mixed>  $payload
     */
    public function syncExisting(Product $product, array $payload): void
    {
        foreach ($payload as $imageId => $meta) {
            $image = $product->images()->whereKey($imageId)->first();

            if (! $image) {
                continue;
            }

            $data = [];

            if (is_array($meta)) {
                if (array_key_exists('is_primary', $meta)) {
                    $data['is_primary'] = (bool) $meta['is_primary'];
                }
                if (array_key_exists('sort_order', $meta)) {
                    $data['sort_order'] = (int) $meta['sort_order'];
                }
                if (array_key_exists('alt', $meta)) {
                    $data['alt'] = mb_substr((string) $meta['alt'], 0, 255);
                }
            } elseif (is_numeric($meta)) {
                $data['sort_order'] = (int) $meta;
            }

            if ($data !== []) {
                $image->update($data);
            }
        }
    }

    /**
     * Hapus gambar berdasarkan id yang dicentang admin untuk dihapus.
     *
     * @param  array<int, mixed>  $ids
     */
    public function deleteByIds(Product $product, array $ids): void
    {
        $ids = array_filter(array_map('intval', $ids));

        if ($ids === []) {
            return;
        }

        $product->images()
            ->whereIn('id', $ids)
            ->get()
            ->each(function ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            });
    }

    /**
     * Hapus gambar lama yang tidak lagi dikirim form.
     *
     * @param  array<int|string, mixed>  $payload
     */
    public function removeMissing(Product $product, array $payload): void
    {
        $keep = [];

        foreach ($payload as $imageId => $meta) {
            $keep[] = (int) $imageId;
        }

        $product->images()
            ->when($keep !== [], fn ($q) => $q->whereNotIn('id', $keep))
            ->get()
            ->each(function ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            });
    }

    public function deleteAll(Product $product): void
    {
        $product->images()->get()->each(function ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        });
    }

    private function isValid(UploadedFile $file): bool
    {
        if ($file->getSize() > 2048 * 1024) {
            return false;
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension());

        return in_array($ext, self::RULES, true);
    }
}