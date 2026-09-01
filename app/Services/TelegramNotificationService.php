<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    protected ?string $botToken;
    protected ?string $chatId;
    protected bool $enabled;

    public function __construct(?int $shopId = null)
    {
        if ($shopId) {
            $this->botToken = Setting::get($shopId, 'telegram_bot_token', '');
            $this->chatId = Setting::get($shopId, 'telegram_chat_id', '');
            $this->enabled = !empty($this->botToken) && !empty($this->chatId);
        } else {
            $this->botToken = null;
            $this->chatId = null;
            $this->enabled = false;
        }
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function testConnection(): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'Telegram غير مُكوّن'];
        }

        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->get("https://api.telegram.org/bot{$this->botToken}/getMe");

            if ($response->successful()) {
                $bot = $response->json('result', []);
                return [
                    'success' => true,
                    'message' => "متصل بالبوت: " . ($bot['username'] ?? 'unknown'),
                ];
            }

            return [
                'success' => false,
                'message' => 'فشل الاتصال: تأكد من التوكن',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage(),
            ];
        }
    }

    public function sendMessage(string $text, array $options = []): bool
    {
        if (!$this->enabled) {
            return false;
        }

        try {
            $payload = array_merge([
                'chat_id' => $this->chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ], $options);

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);

            if (!$response->successful()) {
                Log::error('Telegram send failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendNewOrderNotification(Order $order): bool
    {
        $shop = $order->shop;
        $items = $order->items;
        $itemsText = $items->map(fn($item) => "  • {$item->product_name} × {$item->quantity} = " . number_format($item->total) . " DA")->implode("\n");

        $text = "🔔 <b>طلبية جديدة!</b>\n\n"
            . "📦 <b>رقم الطلب:</b> #{$order->order_number}\n"
            . "👤 <b>العميل:</b> {$order->customer_name}\n"
            . "📱 <b>الهاتف:</b> {$order->customer_phone}\n"
            . "📍 <b>العنوان:</b> {$order->wilaya} - {$order->commune}\n"
            . "💰 <b>المبلغ:</b> " . number_format($order->total) . " DA\n"
            . "💳 <b>الدفع:</b> " . ($order->payment_method === 'cod' ? 'عند الاستلام' : $order->payment_method) . "\n\n"
            . "🛒 <b>المنتجات:</b>\n{$itemsText}\n\n"
            . "🔗 <a href=\"https://t.me/" . ($shop->telegram_bot_username ?? 'yourbot') . "\">عرض في لوحة التحكم</a>";

        return $this->sendMessage($text);
    }

    public function sendStatusChangeNotification(Order $order, string $oldStatus, string $newStatus): bool
    {
        $statusLabels = [
            'new' => 'جديد',
            'confirmed' => 'مؤكد',
            'processing' => 'قيد المعالجة',
            'shipped' => 'مشحون',
            'out_for_delivery' => 'في طريق التوصيل',
            'delivered' => 'تم التوصيل ✅',
            'returned' => 'مرتجع',
            'cancelled' => 'ملغي ❌',
        ];

        $statusEmojis = [
            'confirmed' => '✅',
            'shipped' => '🚚',
            'out_for_delivery' => '🚚',
            'delivered' => '🎉',
            'returned' => '↩️',
            'cancelled' => '❌',
        ];

        $emoji = $statusEmojis[$newStatus] ?? '📋';
        $label = $statusLabels[$newStatus] ?? $newStatus;

        $text = "{$emoji} <b>تحديث حالة الطلب</b>\n\n"
            . "📦 <b>رقم الطلب:</b> #{$order->order_number}\n"
            . "👤 <b>العميل:</b> {$order->customer_name}\n"
            . "💰 <b>المبلغ:</b> " . number_format($order->total) . " DA\n"
            . "📋 <b>الحالة الجديدة:</b> {$label}\n";

        if (!empty($order->tracking_number)) {
            $text .= "🔍 <b>التتبع:</b> {$order->tracking_number}\n";
        }

        if ($newStatus === 'delivered') {
            $text .= "\n🎉 <b>تم التوصيل بنجاح!</b>\n";
            $text .= "💰 المبلغ المطلوب تحصيله: " . number_format($order->total) . " DA\n";
        }

        return $this->sendMessage($text);
    }

    public function sendLowStockAlert(int $shopId, $product): bool
    {
        $tg = new self($shopId);
        if (!$tg->isEnabled()) return false;

        $text = "⚠️ <b>تنبيه: مخزون منخفض!</b>\n\n"
            . "📦 <b>المنتج:</b> {$product->name}\n"
            . "📊 <b>المخزون المتبقي:</b> {$product->stock_quantity} حبة\n"
            . "🔔 <b>الحد الأدنى:</b> {$product->low_stock_threshold} حبة\n";

        return $tg->sendMessage($text);
    }

    public function sendDailySummary(int $shopId): bool
    {
        $shop = \App\Models\Shop::find($shopId);
        if (!$shop) return false;

        $today = now()->toDateString();

        $newOrders = $shop->orders()->whereDate('created_at', $today)->count();
        $deliveredOrders = $shop->orders()->where('status', 'delivered')->whereDate('updated_at', $today)->count();
        $todayRevenue = $shop->orders()->where('status', 'delivered')->whereDate('updated_at', $today)->sum('total');
        $totalRevenue = $shop->orders()->where('status', 'delivered')->sum('total');
        $pendingOrders = $shop->orders()->whereIn('status', ['new', 'confirmed', 'processing'])->count();

        $text = "📊 <b>ملخص يومي - {$shop->name}</b>\n"
            . "📅 {$today}\n\n"
            . "📦 طلبات جديدة اليوم: <b>{$newOrders}</b>\n"
            . "✅ تم توصيلها اليوم: <b>{$deliveredOrders}</b>\n"
            . "💰 إيرادات اليوم: <b>" . number_format($todayRevenue) . " DA</b>\n"
            . "⏳ طلبات بانتظار المعالجة: <b>{$pendingOrders}</b>\n"
            . "💰 إجمالي الإيرادات: <b>" . number_format($totalRevenue) . " DA</b>\n";

        return $this->sendMessage($text);
    }
}
