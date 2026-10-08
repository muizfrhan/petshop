<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_number',
        'user_id',
        'coupon_id',
        'status',
        'customer_name',
        'email',
        'phone',
        'whatsapp',
        'street',
        'province',
        'city',
        'district',
        'postal_code',
        'shipping_method',
        'shipping_address',
        'notes',
        'subtotal',
        'discount',
        'shipping_cost',
        'tax',
        'total',
        'coupon_code',
        'payment_method',
        'paid_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'discount' => 'integer',
            'shipping_cost' => 'integer',
            'tax' => 'integer',
            'total' => 'integer',
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function statusLabel(): string
    {
        return config('petshop.order_statuses')[$this->status] ?? $this->status;
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PAID], true);
    }

    public function canBeReviewed(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }
}