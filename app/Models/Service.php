<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'includes',
        'price',
        'duration_minutes',
        'image',
        'icon',
        'status',
        'featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'duration_minutes' => 'integer',
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class)->latest();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function durationLabel(): string
    {
        $minutes = (int) $this->duration_minutes;

        if ($minutes < 60) {
            return $minutes.' menit';
        }

        $hours = intdiv($minutes, 60);
        $rest = $minutes % 60;

        return $rest > 0 ? "{$hours} jam {$rest} menit" : "{$hours} jam";
    }

    public function includeList(): array
    {
        return collect(explode("\n", (string) $this->includes))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}