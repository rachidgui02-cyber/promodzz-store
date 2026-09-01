<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $ordersByDate = $shop->orders()
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(total), 0) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueChart = $shop->orders()
            ->where('created_at', '>=', now()->subDays(30))
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COALESCE(SUM(total), 0) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = $shop->products()
            ->withSum('orderItems', 'quantity')
            ->withSum('orderItems', 'total')
            ->orderByDesc('order_items_sum_quantity')
            ->limit(10)
            ->get();

        $statusBreakdown = $shop->orders()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('dashboard.stats.index', compact(
            'ordersByDate',
            'revenueChart',
            'topProducts',
            'statusBreakdown'
        ));
    }

    public function api(Request $request)
    {
        $shop = $request->user()->shop;

        $ordersByDate = $shop->orders()
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(total), 0) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueChart = $shop->orders()
            ->where('created_at', '>=', now()->subDays(30))
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COALESCE(SUM(total), 0) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = $shop->products()
            ->withSum('orderItems', 'quantity')
            ->withSum('orderItems', 'total')
            ->orderByDesc('order_items_sum_quantity')
            ->limit(10)
            ->get()
            ->map(fn ($product) => [
                'name' => $product->name,
                'quantity_sold' => $product->order_items_sum_quantity ?? 0,
                'total_revenue' => $product->order_items_sum_total ?? 0,
            ]);

        $statusBreakdown = $shop->orders()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $todayNewOrders = $shop->orders()
            ->whereDate('created_at', today())
            ->where('status', 'new')
            ->count();

        return response()->json([
            'orders_by_date' => $ordersByDate,
            'revenue_chart' => $revenueChart,
            'top_products' => $topProducts,
            'status_breakdown' => $statusBreakdown,
            'today_new_orders' => $todayNewOrders,
        ]);
    }
}
