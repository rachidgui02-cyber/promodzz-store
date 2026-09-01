<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'base_cost',
        'per_item_cost',
        'estimated_days',
        'is_active',
        'config',
        'shop_id',
    ];

    protected function casts(): array
    {
        return [
            'base_cost' => 'decimal:2',
            'per_item_cost' => 'decimal:2',
            'estimated_days' => 'integer',
            'is_active' => 'boolean',
            'config' => 'array',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
