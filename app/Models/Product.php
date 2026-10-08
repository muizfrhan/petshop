<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'description',
        'specification',
        'price',
        'discount_price',
        'stock',
        'weight',
        'status',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'discount_price' => 'integer',
            'stock' => 'integer',
            'weight' => 'integer',
            'featured' => 'boolean',
            'rating_average' => 'float',
            'rating_count' => 'integer',
            'sold_count' => 'integer',
        ];
    }

    /**
     * Terima slug maupun id pada route binding, supaya link lama tetap hidup
     * dan input non-numeric tidak membuat query error di PostgreSQL.
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($field === null && ! is_numeric($value)) {
            return $query->where('slug', $value);
        }

        return parent::resolveRouteBindingQuery($query, $value, $field);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', Review::STATUS_PUBLISHED)->latest();
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        $keyword = '%'.str_replace('%', '\%', $keyword).'%';

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('name', 'ilike', $keyword)
                ->orWhere('sku', 'ilike', $keyword)
                ->orWhere('description', 'ilike', $keyword);
        });
    }

    public function scopeSort(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) desc'),
            'popular' => $query->orderByDesc('sold_count'),
            'rating' => $query->orderByDesc('rating_average'),
            'latest' => $query->latest(),
            default => $query->latest(),
        };
    }

    /**
     * Harga efektif selalu dihitung di backend, tidak pernah dari input frontend.
     */
    public function getFinalPriceAttribute(): int
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (int) $this->discount_price;
        }

        return (int) $this->price;
    }

    public function isOnSale(): bool
    {
        return $this->discount_price !== null
            && (int) $this->discount_price > 0
            && (int) $this->discount_price < (int) $this->price;
    }

    public function discountPercent(): int
    {
        if (! $this->isOnSale() || (int) $this->price <= 0) {
            return 0;
        }

        return (int) round((1 - $this->final_price / $this->price) * 100);
    }

    public function isOutOfStock(): bool
    {
        return (int) $this->stock <= 0;
    }

    public function isLowStock(): bool
    {
        return (int) $this->stock <= (int) config('petshop.low_stock_threshold', 10);
    }

    public function imageUrl(): string
    {
        $image = $this->relationLoaded('primaryImage')
            ? $this->primaryImage->first()
            : $this->primaryImage()->first();

        if ($image) {
            return Storage::disk('public')->url($image->path);
        }

        return asset('images/hero-card.jpg');
    }
}
