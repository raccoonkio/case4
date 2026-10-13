<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'book_id',
        'order_type',
        'rental_term_days',
        'price',
        'starts_at',
        'ends_at',
        'status',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rental_term_days' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function isRental(): bool
    {
        return $this->order_type === 'rental';
    }
}
