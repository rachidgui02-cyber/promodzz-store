<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    const STATUS_LABELS = [
        'new' => 'طلب جديد',
        'confirmed' => 'تم تأكيد الطلب',
        'waiting_callback' => 'في انتظار عودة الاتصال',
        'customer_unavailable' => 'العميل غير متاح',
        'no_answer_1' => 'العميل لم يرد (1)',
        'no_answer_2' => 'العميل لم يرد (2)',
        'no_answer_3' => 'العميل لم يرد (3)',
        'processing' => 'قيد التجهيز',
        'shipped' => 'تم الشحن',
        'out_for_delivery' => 'في الطريق',
        'delivered' => 'تم التوصيل',
        'returned' => 'مرتجع',
        'cancelled' => 'تم إلغاء الطلب',
    ];

    const STATUS_COLORS = [
        'new' => 'emerald',
        'confirmed' => 'green',
        'waiting_callback' => 'amber',
        'customer_unavailable' => 'red',
        'no_answer_1' => 'red',
        'no_answer_2' => 'red',
        'no_answer_3' => 'red',
        'processing' => 'violet',
        'shipped' => 'blue',
        'out_for_delivery' => 'orange',
        'delivered' => 'emerald',
        'returned' => 'rose',
        'cancelled' => 'red',
    ];

    const STATUS_HEX_COLORS = [
        'new' => '#34d399',
        'confirmed' => '#22c55e',
        'waiting_callback' => '#fbbf24',
        'customer_unavailable' => '#f87171',
        'no_answer_1' => '#f87171',
        'no_answer_2' => '#f87171',
        'no_answer_3' => '#f87171',
        'processing' => '#a78bfa',
        'shipped' => '#4f8cff',
        'out_for_delivery' => '#fb923c',
        'delivered' => '#34d399',
        'returned' => '#f472b6',
        'cancelled' => '#ef4444',
    ];

    const CALL_FLOW_STATUSES = ['waiting_callback', 'customer_unavailable', 'no_answer_1', 'no_answer_2', 'no_answer_3'];

    const SOURCE_TYPES = [
        'facebook' => 'فيسبوك',
        'instagram' => 'انستغرام',
        'tiktok' => 'تيك توك',
        'direct' => 'مباشر',
        'other' => 'أخرى',
    ];

    const SOURCE_ICONS = [
        'facebook' => 'f',
        'instagram' => 'Ig',
        'tiktok' => 'Tk',
        'direct' => '↗',
        'other' => '•',
    ];

    public static function getStatusLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? $status;
    }

    public static function getStatusColor(string $status): string
    {
        return self::STATUS_COLORS[$status] ?? 'gray';
    }

    public static function getStatusHexColor(string $status): string
    {
        return self::STATUS_HEX_COLORS[$status] ?? '#6b7280';
    }

    protected $fillable = [
        'shop_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'wilaya',
        'commune',
        'notes',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'source',
        'tracking_number',
        'shipping_company',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'call_attempts',
        'last_call_at',
        'call_notes',
        'stock_decremented',
        'customer_id',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'last_call_at' => 'datetime',
            'stock_decremented' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function shippingCompany(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-';
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', $prefix . $date . '-%')
            ->orderByDesc('order_number')
            ->first();

        if ($lastOrder) {
            $sequence = (int) substr($lastOrder->order_number, -4) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function updateStatus(string $newStatus): void
    {
        $oldStatus = $this->status;

        if ($newStatus === 'confirmed' && !$this->stock_decremented) {
            foreach ($this->items as $oi) {
                if ($oi->product) {
                    $oi->product->decrement('stock_quantity', $oi->quantity);
                }
            }
            $this->update(['stock_decremented' => true]);
        }

        if (in_array($newStatus, ['cancelled', 'no_answer_3', 'returned']) && $this->stock_decremented) {
            foreach ($this->items as $oi) {
                if ($oi->product) {
                    $oi->product->increment('stock_quantity', $oi->quantity);
                }
            }
            $this->update(['stock_decremented' => false]);
        }

        $this->update(['status' => $newStatus]);

        if ($newStatus === 'shipped') {
            $this->update(['shipped_at' => now()]);
        } elseif ($newStatus === 'delivered') {
            $this->update(['delivered_at' => now()]);
        } elseif ($newStatus === 'cancelled') {
            $this->update(['cancelled_at' => now()]);
        }

        $this->statusHistories()->create([
            'status' => $newStatus,
            'notes' => "Status updated from {$oldStatus} to {$newStatus}",
        ]);
    }
}
