<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function track($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('shop')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود']);
        }

        $statusLabels = [
            'new' => 'طلب جديد',
            'confirmed' => 'تم التأكيد',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'out_for_delivery' => 'في طريق التوصيل',
            'delivered' => 'تم التوصيل',
            'returned' => 'مرتجع',
            'cancelled' => 'ملغي',
        ];

        $statusSteps = ['new', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered'];
        $currentStep = array_search($order->status, $statusSteps);
        $isTerminal = in_array($order->status, ['delivered', 'returned', 'cancelled']);

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'status' => $order->status,
                'status_label' => $statusLabels[$order->status] ?? $order->status,
                'total' => $order->total,
                'shipping_cost' => $order->shipping_cost,
                'payment_method' => $order->payment_method,
                'tracking_number' => $order->tracking_number,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
                'delivered_at' => $order->delivered_at?->format('Y-m-d H:i'),
                'items' => $order->items->map(fn($item) => [
                    'name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => $item->product_price,
                ]),
                'progress' => $isTerminal ? 100 : (($currentStep !== false ? $currentStep + 1 : 0) / count($statusSteps) * 100),
                'steps' => $statusSteps,
                'current_step' => $currentStep,
            ],
        ]);
    }
}
