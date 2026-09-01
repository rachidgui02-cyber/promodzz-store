<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'email',
        'address',
        'wilaya',
        'commune',
        'total_orders',
        'total_spent',
        'last_order_at',
        'is_blocked',
        'blocked_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_spent' => 'decimal:2',
            'last_order_at' => 'datetime',
            'is_blocked' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_phone', 'phone')
            ->where('shop_id', $this->shop_id);
    }

    public function block(string $reason = null): void
    {
        $this->update([
            'is_blocked' => true,
            'blocked_reason' => $reason,
        ]);
    }

    public function unblock(): void
    {
        $this->update([
            'is_blocked' => false,
            'blocked_reason' => null,
        ]);
    }

    public function recordOrder(float $amount): void
    {
        $this->increment('total_orders');
        $this->increment('total_spent', $amount);
        $this->update(['last_order_at' => now()]);
    }
}
