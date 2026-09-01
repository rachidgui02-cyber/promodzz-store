<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\DhdShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $totalOrders = $shop->orders()->count();
        $pendingOrders = $shop->orders()->where('status', 'new')->count();
        $shippedOrders = $shop->orders()->where('status', 'shipped')->count();
        $deliveredOrders = $shop->orders()->where('status', 'delivered')->count();
        $cancelledOrders = $shop->orders()->where('status', 'cancelled')->count();
        $outForDelivery = $shop->orders()->where('status', 'out_for_delivery')->count();
        $confirmedOrders = $shop->orders()->where('status', 'confirmed')->count();
        $processingOrders = $shop->orders()->where('status', 'processing')->count();

        // ═══════ DHD / Livraison Statistics ═══════
        $dhd = new DhdShippingService($shop->id);
        $dhdConfig = $dhd->getConfig();

        $dhdParcels = $shop->orders()->whereNotNull('tracking_number')->count();
        $dhdDelivered = $shop->orders()->where('status', 'delivered')->count();
        $dhdReturned = $shop->orders()->where('status', 'returned')->count();
        $dhdInTransit = $shop->orders()->whereIn('status', ['shipped', 'out_for_delivery'])->count();
        $dhdPendingShip = $shop->orders()->whereIn('status', ['new', 'confirmed', 'processing'])
            ->whereNull('tracking_number')->count();

        $totalRevenue = $shop->orders()
            ->where('payment_status', 'paid')
            ->sum('total');

        $pendingRevenue = $shop->orders()
            ->whereIn('status', ['new', 'confirmed', 'processing', 'shipped', 'out_for_delivery'])
            ->where('payment_status', '!=', 'paid')
            ->sum('total');

        $warehouseValue = $shop->products()
            ->sum(DB::raw('buy_price * stock_quantity'));

        $inTransitValue = $shop->orders()
            ->whereIn('status', ['shipped', 'out_for_delivery'])
            ->sum('total');

        $netWorth = $warehouseValue + $totalRevenue;

        $stats = [
            'warehouse'        => $warehouseValue,
            'in_transit'       => $inTransitValue,
            'pending_cash'     => $pendingRevenue,
            'net_worth'        => $netWorth,
            'total_orders'     => $totalOrders,
            'awaiting_shipment'=> $confirmedOrders + $processingOrders,
            'in_delivery'      => $outForDelivery,
            'delivered'        => $deliveredOrders,
            // DHD / Livraison
            'dhd_parcels'      => $dhdParcels,
            'dhd_delivered'    => $dhdDelivered,
            'dhd_returned'     => $dhdReturned,
            'dhd_in_transit'   => $dhdInTransit,
            'dhd_pending_ship' => $dhdPendingShip,
        ];

        $recentOrders = $shop->orders()
            ->with('items')
            ->latest()
            ->limit(10)
            ->get();

        $lowStockProducts = $shop->products()
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'shop',
            'stats',
            'recentOrders',
            'lowStockProducts',
            'dhdConfig'
        ));
    }
}
