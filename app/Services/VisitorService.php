<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VisitorService
{
    public static function track(Shop $shop, string $url, ?string $referrer = null): void
    {
        $ip = request()->ip() ?? '0.0.0.0';
        $ipHash = hash('sha256', $ip . $shop->id);
        $ua = request()->userAgent() ?? '';
        $device = self::detectDevice($ua);

        // Unique per hour per IP per shop
        $recentlyLogged = DB::table('visitor_logs')
            ->where('shop_id', $shop->id)
            ->where('ip_hash', $ipHash)
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if ($recentlyLogged) return;

        DB::table('visitor_logs')->insert([
            'shop_id' => $shop->id,
            'ip_hash' => $ipHash,
            'url' => $url,
            'referrer' => $referrer,
            'user_agent' => substr($ua, 0, 500),
            'device_type' => $device,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function getTodayStats(Shop $shop): array
    {
        $today = DB::table('visitor_logs')
            ->where('shop_id', $shop->id)
            ->whereDate('created_at', today());

        $totalVisitors = (clone $today)->count();
        $uniqueVisitors = (clone $today)->distinct('ip_hash')->count('ip_hash');

        $devices = (clone $today)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type');

        $hourly = (clone $today)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');

        return [
            'total_visitors' => $totalVisitors,
            'unique_visitors' => $uniqueVisitors,
            'devices' => $devices,
            'hourly' => $hourly,
        ];
    }

    public static function getWeeklyStats(Shop $shop): array
    {
        return DB::table('visitor_logs')
            ->where('shop_id', $shop->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'), DB::raw('COUNT(DISTINCT ip_hash) as unique_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private static function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (preg_match('/tablet|ipad/', $ua)) return 'tablet';
        if (preg_match('/mobile|android|iphone/', $ua)) return 'mobile';
        return 'desktop';
    }
}
