<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Services\TelegramNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Send daily e-commerce report via Telegram';

    public function handle()
    {
        $shops = Shop::all();

        foreach ($shops as $shop) {
            $tg = new TelegramNotificationService($shop->id);
            if (!$tg->isEnabled()) continue;

            $today = Carbon::today();

            $newOrders = $shop->orders()->whereDate('created_at', $today)->count();
            $confirmedOrders = $shop->orders()->where('status', 'confirmed')->whereDate('updated_at', $today)->count();
            $deliveredToday = $shop->orders()->where('status', 'delivered')->whereDate('delivered_at', $today)->count();
            $returnedToday = $shop->orders()->where('status', 'returned')->whereDate('updated_at', $today)->count();
            $cancelledToday = $shop->orders()->where('status', 'cancelled')->whereDate('updated_at', $today)->count();

            $revenueToday = (clone $shop->orders())
                ->where('status', 'delivered')
                ->whereDate('delivered_at', $today)
                ->sum('total');

            $totalDelivered = $shop->orders()->where('status', 'delivered')->count();
            $totalOrders = $shop->orders()->count();
            $deliveryRate = $totalOrders > 0 ? round(($totalDelivered / $totalOrders) * 100, 1) : 0;

            $pendingOrders = $shop->orders()->whereIn('status', ['new', 'waiting_callback', 'no_answer_1', 'no_answer_2', 'no_answer_3', 'customer_unavailable'])->count();

            $lowStockProducts = $shop->products()
                ->where('is_active', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                ->where('stock_quantity', '>', 0)
                ->get();

            $text = "📊 <b>تقرير يومي - {$shop->name}</b>\n";
            $text .= "📅 " . $today->format('d/m/Y') . "\n\n";

            $text .= "📦 <b>الطلبات:</b>\n";
            $text .= "  • جديدة اليوم: <b>{$newOrders}</b>\n";
            $text .= "  • تم تأكيدها: <b>{$confirmedOrders}</b>\n";
            $text .= "  • تم توصيلها: <b>{$deliveredToday}</b>\n";
            $text .= "  • مرتجعة: <b>{$returnedToday}</b>\n";
            $text .= "  • ملغاة: <b>{$cancelledToday}</b>\n";
            $text .= "  • بانتظار المتابعة: <b>{$pendingOrders}</b>\n\n";

            $text .= "💰 <b>المالية:</b>\n";
            $text .= "  • إيرادات اليوم: <b>" . number_format($revenueToday) . " د.ج</b>\n";
            $text .= "  • نسبة التوصيل: <b>{$deliveryRate}%</b>\n\n";

            if ($lowStockProducts->isNotEmpty()) {
                $text .= "⚠️ <b>منتجات مخزونها منخفض:</b>\n";
                foreach ($lowStockProducts as $p) {
                    $text .= "  • {$p->name}: <b>{$p->stock_quantity}</b> حبة\n";
                }
                $text .= "\n";
            }

            $text .= "📈 <b>الإجمالي:</b> {$totalDelivered}/{$totalOrders} طلبية مسلّمة";

            $tg->sendMessage($text);
        }

        $this->info('Daily report sent successfully.');
    }
}
