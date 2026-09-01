<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'phone',
        'address',
        'wilaya',
        'commune',
        'default_shipping_cost',
        'facebook_pixel_id',
        'access_token',
        'api_version',
        'is_active',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'default_shipping_cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function shippingCompanies(): HasMany
    {
        return $this->hasMany(ShippingCompany::class);
    }

    public function wilayaRates(): HasMany
    {
        return $this->hasMany(WilayaShippingRate::class);
    }

    public function getOrdersCount(): int
    {
        return $this->orders()->count();
    }

    public function getProductsCount(): int
    {
        return $this->products()->count();
    }

    public function getRevenue(): string
    {
        return (string) $this->orders()
            ->where('payment_status', 'paid')
            ->sum('total');
    }

    public function getPendingOrders(): int
    {
        return $this->orders()->where('status', 'pending')->count();
    }
}
