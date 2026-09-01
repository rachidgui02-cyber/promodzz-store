<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'shop_id',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public static function get(int $shopId, string $key, $default = null)
    {
        $setting = static::where('shop_id', $shopId)->where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(int $shopId, string $key, $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['shop_id' => $shopId, 'key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}
