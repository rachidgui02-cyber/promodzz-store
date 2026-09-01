<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;
        
        $pendingCalls = $shop->orders()
            ->whereIn('status', ['new', 'waiting_callback', 'no_answer_1', 'no_answer_2', 'customer_unavailable'])
            ->orderBy('call_attempts', 'asc')
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();

        $todayStats = [
            'total_calls' => $shop->orders()->whereDate('updated_at', today())->where('call_attempts', '>', 0)->count(),
            'confirmed' => $shop->orders()->whereDate('updated_at', today())->where('status', 'confirmed')->count(),
            'cancelled' => $shop->orders()->whereDate('updated_at', today())->where('status', 'cancelled')->count(),
            'pending' => $pendingCalls->count(),
        ];

        return view('dashboard.call-center.index', compact('pendingCalls', 'todayStats'));
    }

    public function assignNext(Request $request)
    {
        $shop = $request->user()->shop;
        $order = $shop->orders()
            ->whereIn('status', ['new', 'waiting_callback', 'no_answer_1', 'no_answer_2', 'customer_unavailable'])
            ->orderBy('call_attempts', 'asc')
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'لا توجد طلبات بانتظار المكالمة']);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'total' => $order->total,
                'wilaya' => $order->wilaya,
                'items' => $order->items->pluck('product_name', 'quantity'),
                'status' => $order->status,
                'call_attempts' => $order->call_attempts,
                'notes' => $order->notes,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }
}
