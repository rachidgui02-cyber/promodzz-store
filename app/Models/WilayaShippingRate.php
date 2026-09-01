<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WilayaShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'wilaya_code',
        'wilaya_name',
        'domicile_cost',
        'stop_desk_cost',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'domicile_cost' => 'decimal:2',
            'stop_desk_cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public static function getCostForWilaya($shopId, $wilayaName, $deliveryType = 'home'): float
    {
        $rate = static::where('shop_id', $shopId)
            ->where('wilaya_name', $wilayaName)
            ->first();

        if (!$rate || !$rate->is_active) {
            $shop = Shop::find($shopId);
            return $shop->default_shipping_cost ?? 600;
        }

        return $deliveryType === 'stop_desk'
            ? (float) $rate->stop_desk_cost
            : (float) $rate->domicile_cost;
    }
}
