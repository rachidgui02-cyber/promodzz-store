<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use App\Models\WilayaShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;
        $shopId = $shop->id;
        $period = $request->get('period', 'all');
        $dateFilter = $this->getDateFilter($period, $request);

        $summary = $this->getFinancialSummary($shopId, $dateFilter);
        $productProfits = $this->getProductProfits($shopId, $dateFilter);
        $chartData = $this->getChartData($shopId, $dateFilter);

        $products = $shop->products()
            ->with('category')
            ->select('products.*')
            ->selectRaw('
                COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_items.quantity ELSE 0 END), 0) as total_delivered,
                COALESCE(SUM(CASE WHEN orders.status IN ("confirmed","processing","shipped","out_for_delivery") THEN order_items.quantity ELSE 0 END), 0) as in_transit_count,
                COALESCE(SUM(CASE WHEN orders.status = "returned" THEN order_items.quantity ELSE 0 END), 0) as returned_count
            ')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('products.id')
            ->orderBy('products.name')
            ->paginate(25)
            ->withQueryString();

        return view('dashboard.warehouse.index', compact('summary', 'productProfits', 'chartData', 'products'));
    }

    public function expenses(Request $request)
    {
        $shop = $request->user()->shop;
        $period = $request->get('period', 'all');
        $dateFilter = $this->getDateFilter($period, $request);

        $expenses = Expense::where('shop_id', $shop->id)
            ->with('product')
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('date', $df))
            ->latest('date')
            ->paginate(25)
            ->withQueryString();

        $totalExpenses = Expense::where('shop_id', $shop->id)
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('date', $df))
            ->sum('amount');

        $byType = Expense::where('shop_id', $shop->id)
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('date', $df))
            ->select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get();

        $products = $shop->products()->orderBy('name')->get();

        return view('dashboard.warehouse.expenses', compact('expenses', 'totalExpenses', 'byType', 'products'));
    }

    public function wallet(Request $request)
    {
        $shop = $request->user()->shop;
        $shopId = $shop->id;

        $deliveredUnpaid = $shop->orders()
            ->where('status', 'delivered')
            ->where('payment_status', '!=', 'paid')
            ->sum('total');

        $deliveredPaid = $shop->orders()
            ->where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->sum('total');

        $totalRevenue = $shop->orders()
            ->whereIn('status', ['delivered', 'out_for_delivery', 'shipped'])
            ->sum('total');

        $totalOrders = $shop->orders()
            ->whereIn('status', ['delivered', 'out_for_delivery', 'shipped'])
            ->count();

        $totalUnits = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.shop_id', $shopId)
            ->whereIn('orders.status', ['delivered', 'out_for_delivery', 'shipped'])
            ->sum('order_items.quantity');

        $deliveredUnits = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.shop_id', $shopId)
            ->where('orders.status', 'delivered')
            ->sum('order_items.quantity');

        $payouts = $shop->orders()
            ->where('status', 'delivered')
            ->latest('delivered_at')
            ->paginate(50)
            ->withQueryString();

        return view('dashboard.warehouse.wallet', compact(
            'deliveredUnpaid', 'deliveredPaid', 'totalRevenue',
            'totalOrders', 'totalUnits', 'deliveredUnits', 'payouts'
        ));
    }

    public function orders(Request $request)
    {
        $shop = $request->user()->shop;
        $period = $request->get('period', 'all');
        $search = $request->get('search', '');
        $dateFilter = $this->getDateFilter($period, $request);

        $orders = $shop->orders()
            ->with('items.product')
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('created_at', $df))
            ->when($search, function ($q, $s) {
                $q->where(function ($w) use ($s) {
                    $w->where('order_number', 'like', "%{$s}%")
                      ->orWhere('customer_name', 'like', "%{$s}%")
                      ->orWhere('customer_phone', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.warehouse.orders', compact('orders'));
    }

    public function orderTimeline(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $order = $shop->orders()->with(['items.product', 'statusHistories'])->findOrFail($id);

        $histories = $order->statusHistories()->latest()->get()->map(fn($h) => [
            'label' => Order::getStatusLabel($h->status),
            'color' => Order::getStatusColor($h->status),
            'timestamp' => $h->created_at->format('Y/m/d H:i'),
            'notes' => $h->notes,
        ]);

        return response()->json([
            'order' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'wilaya' => $order->wilaya,
                'total' => number_format($order->total + $order->shipping_cost),
            ],
            'history' => $histories,
        ]);
    }

    public function storeExpense(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['shop_id'] = $shop->id;

        Expense::create($validated);

        return back()->with('success', 'تم تسجيل المصروف بنجاح.');
    }

    public function deleteExpense(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $expense = Expense::where('shop_id', $shop->id)->findOrFail($id);
        $expense->delete();

        return back()->with('success', 'تم حذف المصروف.');
    }

    public function updateStock(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $product = $shop->products()->findOrFail($id);

        $validated = $request->validate([
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        $old = $product->stock_quantity;
        $product->update(['stock_quantity' => $validated['stock_quantity']]);

        if ($validated['stock_quantity'] <= $product->low_stock_threshold && $validated['stock_quantity'] > 0 && $old > $product->low_stock_threshold) {
            \App\Services\TelegramNotificationService::sendLowStockAlert($shop->id, $product);
        }

        return back()->with('success', "تم تعديل ستوك \"{$product->name}\" من {$old} إلى {$validated['stock_quantity']}.");
    }

    private function getDateFilter(string $period, Request $request): ?array
    {
        return match ($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            '7days' => [now()->subDays(7)->startOfDay(), now()->endOfDay()],
            '30days' => [now()->subDays(30)->startOfDay(), now()->endOfDay()],
            'month' => $request->get('month')
                ? [now()->parse($request->get('month'))->startOfMonth(), now()->parse($request->get('month'))->endOfMonth()]
                : null,
            default => null,
        };
    }

    private function getFinancialSummary(int $shopId, ?array $dateFilter): array
    {
        $orderQuery = Order::where('shop_id', $shopId);
        if ($dateFilter) {
            $orderQuery->whereBetween('created_at', $dateFilter);
        }

        $successStatuses = ['delivered', 'out_for_delivery', 'shipped', 'confirmed', 'processing'];

        $grossRevenue = (clone $orderQuery)
            ->whereIn('status', $successStatuses)
            ->get()
            ->sum(fn($o) => $o->total + $o->shipping_cost);

        $netProductSales = (clone $orderQuery)
            ->whereIn('status', $successStatuses)
            ->sum('total');

        $totalExpenses = Expense::where('shop_id', $shopId)
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('date', $df))
            ->sum('amount');

        $productCost = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.shop_id', $shopId)
            ->whereIn('orders.status', $successStatuses)
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('orders.created_at', $df))
            ->selectRaw('COALESCE(SUM(products.buy_price * order_items.quantity), 0)')
            ->value('COALESCE(SUM(products.buy_price * order_items.quantity), 0)');

        $totalShippingPaidToCarrier = 0;
        $activeOrders = (clone $orderQuery)->whereIn('status', $successStatuses)->get(['wilaya', 'notes', 'status']);
        foreach ($activeOrders as $order) {
            $isStopDesk = str_contains($order->notes ?? '', 'المكتب');
            $deliveryType = $isStopDesk ? 'stop_desk' : 'home';
            $totalShippingPaidToCarrier += WilayaShippingRate::getCostForWilaya($shopId, $order->wilaya, $deliveryType);
        }

        $netProfit = $grossRevenue - ($totalExpenses + $productCost);
        $roi = $productCost > 0 ? round(($netProfit / $productCost) * 100, 1) : 0;

        $successfulOrders = (clone $orderQuery)->whereIn('status', ['delivered'])->count();

        $capitalInStock = Product::where('shop_id', $shopId)
            ->selectRaw('COALESCE(SUM(buy_price * stock_quantity), 0)')
            ->value('COALESCE(SUM(buy_price * stock_quantity), 0)');

        $returnedBuyCost = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.shop_id', $shopId)
            ->where('orders.status', 'returned')
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('orders.created_at', $df))
            ->selectRaw('COALESCE(SUM(products.buy_price * order_items.quantity), 0)')
            ->value('COALESCE(SUM(products.buy_price * order_items.quantity), 0)');

        return [
            'gross_revenue' => (float) $grossRevenue,
            'net_product_sales' => (float) $netProductSales,
            'total_expenses' => (float) $totalExpenses,
            'product_cost' => (float) $productCost,
            'net_profit' => (float) $netProfit,
            'roi' => $roi,
            'successful_orders' => $successfulOrders,
            'capital_in_stock' => (float) $capitalInStock,
            'returned_buy_cost' => (float) $returnedBuyCost,
            'shipping_paid' => (float) $totalShippingPaidToCarrier,
        ];
    }

    private function getProductProfits(int $shopId, ?array $dateFilter): array
    {
        $successStatuses = ['delivered', 'out_for_delivery', 'shipped', 'confirmed', 'processing'];

        return Product::where('products.shop_id', $shopId)
            ->select('products.id', 'products.name', 'products.image', 'products.buy_price', 'products.sell_price')
            ->selectRaw('
                COALESCE(SUM(CASE WHEN orders.status IN ("delivered","out_for_delivery","shipped","confirmed","processing") THEN order_items.quantity ELSE 0 END), 0) as units_sold,
                COALESCE(SUM(CASE WHEN orders.status IN ("delivered","out_for_delivery","shipped","confirmed","processing") THEN (order_items.quantity * products.sell_price) ELSE 0 END), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN orders.status IN ("delivered","out_for_delivery","shipped","confirmed","processing") THEN (order_items.quantity * products.buy_price) ELSE 0 END), 0) as total_cost
            ')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($dateFilter, fn($q, $df) => $q->whereBetween('orders.created_at', $df))
            ->groupBy('products.id')
            ->havingRaw('units_sold > 0')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'image' => $p->image,
                'buy_price' => (float) $p->buy_price,
                'sell_price' => (float) $p->sell_price,
                'units_sold' => (int) $p->units_sold,
                'total_revenue' => (float) $p->total_revenue,
                'total_cost' => (float) $p->total_cost,
                'total_profit' => (float) ($p->total_revenue - $p->total_cost),
                'avg_cost' => $p->units_sold > 0 ? round($p->total_cost / $p->units_sold, 2) : 0,
                'avg_profit' => $p->units_sold > 0 ? round(($p->total_revenue - $p->total_cost) / $p->units_sold, 2) : 0,
            ])
            ->toArray();
    }

    private function getChartData(int $shopId, ?array $dateFilter): array
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'label' => $date->format('M'),
                'start' => $date->copy()->startOfMonth(),
                'end' => $date->copy()->endOfMonth(),
            ]);
        }

        $successStatuses = ['delivered', 'out_for_delivery', 'shipped', 'confirmed', 'processing'];

        $monthlyProfit = $months->map(function ($m) use ($shopId, $successStatuses) {
            $revenue = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.shop_id', $shopId)
                ->whereIn('orders.status', $successStatuses)
                ->whereBetween('orders.created_at', [$m['start'], $m['end']])
                ->selectRaw('COALESCE(SUM(orders.total), 0)')
                ->value('COALESCE(SUM(orders.total), 0)');

            $cost = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.shop_id', $shopId)
                ->whereIn('orders.status', $successStatuses)
                ->whereBetween('orders.created_at', [$m['start'], $m['end']])
                ->selectRaw('COALESCE(SUM(products.buy_price * order_items.quantity), 0)')
                ->value('COALESCE(SUM(products.buy_price * order_items.quantity), 0)');

            $expenses = Expense::where('shop_id', $shopId)
                ->whereBetween('date', [$m['start'], $m['end']])
                ->sum('amount');

            return [
                'month' => $m['label'],
                'profit' => (float) ($revenue - $cost - $expenses),
            ];
        })->toArray();

        $statusDistribution = DB::table('orders')
            ->where('shop_id', $shopId)
            ->whereIn('status', ['delivered', 'returned', 'cancelled', 'shipped', 'out_for_delivery'])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return [
            'monthly_profit' => $monthlyProfit,
            'status_distribution' => $statusDistribution,
        ];
    }
}
