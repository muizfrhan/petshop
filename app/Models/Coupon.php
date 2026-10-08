<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_purchase',
        'quota',
        'used_count',
        'status',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'min_purchase' => 'integer',
            'quota' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function isValidFor(int $subtotal): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return false;
        }
        if ($subtotal < $this->min_purchase) {
            return false;
        }

        return true;
    }

    public function discountFor(int $subtotal): int
    {
        if (! $this->isValidFor($subtotal)) {
            return 0;
        }

        return $this->type === 'percent'
            ? (int) floor($subtotal * ($this->value / 100))
            : min((int) $this->value, $subtotal);
    }
}