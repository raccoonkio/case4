<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category',
        'published_year',
        'description',
        'preview_path',
        'price',
        'rental_price_14_days',
        'rental_price_30_days',
        'rental_price_90_days',
        'status',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'published_year' => 'integer',
            'price' => 'decimal:2',
            'rental_price_14_days' => 'decimal:2',
            'rental_price_30_days' => 'decimal:2',
            'rental_price_90_days' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function rentalPrice(int $termDays): float
    {
        return match ($termDays) {
            14 => (float) $this->rental_price_14_days,
            30 => (float) $this->rental_price_30_days,
            90 => (float) $this->rental_price_90_days,
            default => 0,
        };
    }
}
