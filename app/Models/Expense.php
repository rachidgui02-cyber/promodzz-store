<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    const TYPES = [
        'shipping' => 'نقل',
        'advertising' => 'إعلانات',
        'packaging' => 'تغليف',
        'rent' => 'إيجار',
        'salaries' => 'رواتب',
        'purchase' => 'شراء',
        'other' => 'أخرى',
    ];

    protected $fillable = [
        'shop_id',
        'product_id',
        'type',
        'description',
        'amount',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
