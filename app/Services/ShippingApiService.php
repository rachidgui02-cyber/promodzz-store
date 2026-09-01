<?php
namespace App\Services;

use App\Models\Order;
use App\Models\ShippingCompany;
use Illuminate\Support\Str;

class ShippingApiService
{
    public function createShipment(Order $order, ShippingCompany $company): array
    {
        $tracking = match($company->slug) {
            'yalidine' => 'YAL-' . str_pad($order->id, 8, '0', STR_PAD_LEFT),
            'zr-express' => 'ZRE-' . strtoupper(dechex($order->id)) . '-' . Str::random(3),
            default => strtoupper($company->slug) . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
        };

        return [
            'success' => true,
            'tracking_number' => $tracking,
            'shipping_company' => $company->name,
            'estimated_days' => $company->estimated_days,
            'message' => "تم إرسال الطلب بنجاح إلى {$company->name}. رقم التتبع: {$tracking}",
        ];
    }

    public function syncOrderStatus(Order $order, ShippingCompany $company): array
    {
        $currentStatus = $order->status;

        $simulatedStatuses = [
            'confirmed' => ['processing', 0.3],
            'processing' => ['shipped', 0.7],
            'shipped' => ['delivered', 0.6],
            'out_for_delivery' => ['delivered', 0.8],
        ];

        if (!isset($simulatedStatuses[$currentStatus])) {
            return [
                'success' => true,
                'changed' => false,
                'message' => 'الحالة محدثة بالفعل',
            ];
        }

        [$newStatus, $probability] = $simulatedStatuses[$currentStatus];

        $shouldChange = ($order->id % 10) / 10 < $probability;

        if ($shouldChange) {
            if ($newStatus === 'delivered' && $order->id % 7 === 0) {
                $newStatus = 'returned';
            }

            return [
                'success' => true,
                'changed' => true,
                'new_status' => $newStatus,
                'old_status' => $currentStatus,
                'message' => "تم تحديث حالة الطلب من '{$currentStatus}' إلى '{$newStatus}'",
            ];
        }

        return [
            'success' => true,
            'changed' => false,
            'message' => 'لا توجد تحديثات للحالة الحالية',
        ];
    }

    public function getAvailableCompanies($shopId): \Illuminate\Database\Eloquent\Collection
    {
        return ShippingCompany::where('shop_id', $shopId)->where('is_active', true)->get();
    }
}
