<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgentStatsController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $today = Carbon::today();
        $week = Carbon::now()->startOfWeek();
        $month = Carbon::now()->startOfMonth();

        $allOrders = $shop->orders();

        $todayStats = $this->getStats(clone $allOrders, $today);
        $weekStats = $this->getStats(clone $allOrders, $week);
        $monthStats = $this->getStats(clone $allOrders, $month);
        $allTimeStats = $this->getStats(clone $allOrders, null);

        $recentCalls = $shop->orders()
            ->where('call_attempts', '>', 0)
            ->latest('last_call_at')
            ->limit(20)
            ->get()
            ->map(function ($order) {
                return [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'wilaya' => $order->wilaya,
                    'total' => $order->total,
                    'status' => $order->status,
                    'status_label' => \App\Models\Order::getStatusLabel($order->status),
                    'status_color' => \App\Models\Order::getStatusColor($order->status),
                    'call_attempts' => $order->call_attempts,
                    'last_call_at' => $order->last_call_at?->format('d/m H:i'),
                ];
            });

        return view('dashboard.stats.agents', compact(
            'todayStats', 'weekStats', 'monthStats', 'allTimeStats', 'recentCalls'
        ));
    }

    private function getStats($query, $from): array
    {
        if ($from) {
            $query->where('created_at', '>=', $from);
        }

        $total = (clone $query)->count();
        $called = (clone $query)->where('call_attempts', '>', 0)->count();
        $confirmed = (clone $query)->where('status', 'confirmed')
            ->orWhere(function ($q) {
                $q->whereIn('status', ['processing', 'shipped', 'out_for_delivery', 'delivered']);
            })->count();
        $delivered = (clone $query)->where('status', 'delivered')->count();
        $returned = (clone $query)->where('status', 'returned')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();
        $pending = (clone $query)->where('status', 'new')->where('call_attempts', 0)->count();

        return [
            'total' => $total,
            'called' => $called,
            'confirmed' => $confirmed,
            'delivered' => $delivered,
            'returned' => $returned,
            'cancelled' => $cancelled,
            'pending' => $pending,
            'confirm_rate' => $called > 0 ? round(($confirmed / $called) * 100, 1) : 0,
            'delivery_rate' => $confirmed > 0 ? round(($delivered / $confirmed) * 100, 1) : 0,
            'return_rate' => $delivered > 0 ? round(($returned / $delivered) * 100, 1) : 0,
        ];
    }
}
