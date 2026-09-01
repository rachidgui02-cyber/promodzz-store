<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function scan()
    {
        return view('dashboard.returns.scan');
    }

    public function process(Request $request)
    {
        $shop = $request->user()->shop;

        $request->validate([
            'code' => 'required|string',
        ]);

        $code = trim($request->code);

        $order = $shop->orders()
            ->where('order_number', $code)
            ->orWhere('tracking_number', $code)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "لم يتم العثور على طلبية بالكود: {$code}",
            ]);
        }

        if ($order->status === 'returned') {
            return response()->json([
                'success' => false,
                'message' => "الطلبية #{$order->order_number} مرتجعة مسبقاً",
            ]);
        }

        if (!in_array($order->status, ['delivered', 'out_for_delivery', 'shipped', 'processing', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => "لا يمكن استلام مرتجع للحالة الحالية: " . Order::getStatusLabel($order->status),
            ]);
        }

        DB::beginTransaction();

        try {
            $order->updateStatus('returned');

            DB::commit();

            $itemName = $order->items->first()?->product_name ?? '-';

            return response()->json([
                'success' => true,
                'message' => "تم استلام المرتجع بنجاح",
                'order' => [
                    'number' => $order->order_number,
                    'customer' => $order->customer_name,
                    'phone' => $order->customer_phone,
                    'wilaya' => $order->wilaya,
                    'total' => number_format($order->total, 0),
                    'product' => $itemName,
                    'quantity' => $order->items->first()?->quantity ?? 0,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage(),
            ]);
        }
    }
}
