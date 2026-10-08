<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceBooking extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_RESCHEDULED = 'rescheduled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'service_id',
        'customer_name',
        'phone',
        'email',
        'pet_name',
        'pet_type',
        'pet_breed',
        'pet_weight',
        'booking_date',
        'booking_time',
        'notes',
        'status',
        'price',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'pet_weight' => 'float',
            'price' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('booking_date', today());
    }

    public function statusLabel(): string
    {
        return config('petshop.booking_statuses')[$this->status] ?? $this->status;
    }
}