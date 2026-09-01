<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class FraudDetectionService
{
    /**
     * Phone numbers blacklisted per shop (stored in settings)
     */
    private array $blacklistedPhones = [];

    private int $shopId;

    public function __construct(int $shopId)
    {
        $this->shopId = $shopId;
        $this->loadBlacklist();
    }

    /**
     * Load blacklisted phones from shop settings
     */
    private function loadBlacklist(): void
    {
        $raw = DB::table('settings')
            ->where('shop_id', $this->shopId)
            ->where('key', 'blacklisted_phones')
            ->value('value');

        $this->blacklistedPhones = $raw ? json_decode($raw, true) : [];
    }

    /**
     * Full fraud check on an order — returns risk score + reasons
     */
    public function checkOrder(array $orderData): array
    {
        $reasons = [];
        $score = 0;

        // 1. Blacklisted phone
        $phone = $this->normalizePhone($orderData['customer_phone'] ?? '');
        if (in_array($phone, $this->blacklistedPhones)) {
            $reasons[] = 'الهاتف محظور (قائمة سوداء)';
            $score += 50;
        }

        // 2. Duplicate phone within 24h
        $recentByPhone = Order::where('shop_id', $this->shopId)
            ->where('customer_phone', 'like', "%{$phone}%")
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($recentByPhone >= 3) {
            $reasons[] = "{$recentByPhone} طلبات بنفس الهاتف في آخر 24 ساعة";
            $score += 30;
        } elseif ($recentByPhone >= 2) {
            $reasons[] = "طلبان بنفس الهاتف في آخر 24 ساعة";
            $score += 15;
        }

        // 3. Duplicate full name + phone within 48h (potential fake repeat)
        $recentByNamePhone = Order::where('shop_id', $this->shopId)
            ->where('customer_phone', 'like', "%{$phone}%")
            ->where('customer_name', $orderData['customer_name'] ?? '')
            ->where('created_at', '>=', now()->subHours(48))
            ->count();

        if ($recentByNamePhone >= 2) {
            $reasons[] = "نفس الاسم والهاتف مكرر خلال 48 ساعة";
            $score += 20;
        }

        // 4. Suspicious name patterns
        $name = $orderData['customer_name'] ?? '';
        if (strlen($name) < 3) {
            $reasons[] = 'الاسم قصير جداً';
            $score += 10;
        }
        if (preg_match('/^(\d+)$/', $name)) {
            $reasons[] = 'الاسم أرقام فقط';
            $score += 25;
        }
        if (preg_match('/test|تجربة|fake/i', $name)) {
            $reasons[] = 'اسم يحتوي على كلمات مشبوهة';
            $score += 30;
        }

        // 5. Phone validation
        if (strlen($phone) < 9 || !ctype_digit($phone)) {
            $reasons[] = 'رقم الهاتف غير صحيح';
            $score += 15;
        }

        // 6. Unusual order amount (very high)
        $total = $orderData['total'] ?? 0;
        $avgOrder = $this->getAverageOrderAmount();
        if ($avgOrder > 0 && $total > $avgOrder * 3) {
            $reasons[] = 'المبلغ أعلى من 3 أضعاف متوسط الطلبات';
            $score += 10;
        }

        // 7. Orders per phone per day limit
        $todayCount = Order::where('shop_id', $this->shopId)
            ->where('customer_phone', 'like', "%{$phone}%")
            ->whereDate('created_at', today())
            ->count();

        if ($todayCount >= 5) {
            $reasons[] = "{$todayCount} طلبات بنفس الهاتف اليوم";
            $score += 40;
        }

        // Determine risk level
        $riskLevel = 'low';
        if ($score >= 50) {
            $riskLevel = 'high';
        } elseif ($score >= 25) {
            $riskLevel = 'medium';
        }

        return [
            'score' => min($score, 100),
            'risk_level' => $riskLevel,
            'reasons' => $reasons,
            'is_suspicious' => $score >= 25,
            'should_block' => $score >= 50,
        ];
    }

    /**
     * Get average order amount for this shop
     */
    private function getAverageOrderAmount(): float
    {
        return Order::where('shop_id', $this->shopId)
            ->where('created_at', '>=', now()->subDays(30))
            ->avg('total') ?? 0;
    }

    /**
     * Normalize phone number (remove spaces, dashes, etc.)
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)\+]/', '', $phone);
        // Remove leading zeros for comparison
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        return $phone;
    }

    // ═══════════════════════════════════════════
    //  Blacklist Management
    // ═══════════════════════════════════════════

    /**
     * Add phone to blacklist
     */
    public function addToBlacklist(string $phone): bool
    {
        $phone = $this->normalizePhone($phone);
        if (in_array($phone, $this->blacklistedPhones)) {
            return false;
        }

        $this->blacklistedPhones[] = $phone;
        $this->saveBlacklist();
        return true;
    }

    /**
     * Remove phone from blacklist
     */
    public function removeFromBlacklist(string $phone): bool
    {
        $phone = $this->normalizePhone($phone);
        $index = array_search($phone, $this->blacklistedPhones);
        if ($index === false) {
            return false;
        }

        array_splice($this->blacklistedPhones, $index, 1);
        $this->saveBlacklist();
        return true;
    }

    /**
     * Get all blacklisted phones
     */
    public function getBlacklist(): array
    {
        return $this->blacklistedPhones;
    }

    /**
     * Check if a phone is blacklisted
     */
    public function isBlacklisted(string $phone): bool
    {
        return in_array($this->normalizePhone($phone), $this->blacklistedPhones);
    }

    /**
     * Save blacklist to settings
     */
    private function saveBlacklist(): void
    {
        DB::table('settings')->updateOrInsert(
            ['shop_id' => $this->shopId, 'key' => 'blacklisted_phones'],
            ['value' => json_encode($this->blacklistedPhones), 'updated_at' => now()]
        );
    }

    /**
     * Get fraud stats for dashboard
     */
    public function getStats(): array
    {
        $today = Order::where('shop_id', $this->shopId)->whereDate('created_at', today());
        $last30 = Order::where('shop_id', $this->shopId)->where('created_at', '>=', now()->subDays(30));

        return [
            'blacklisted_count' => count($this->blacklistedPhones),
            'today_total' => (clone $today)->count(),
            'today_new' => (clone $today)->where('status', 'new')->count(),
            'blocked_today' => (clone $today)->where('status', 'cancelled')->count(),
            'high_risk_last_30d' => (clone $last30)->where('notes', 'like', '%[FRAUD]%')->count(),
        ];
    }
}
