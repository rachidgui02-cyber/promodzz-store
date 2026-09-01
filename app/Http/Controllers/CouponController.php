<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $coupons = $shop->coupons()
            ->latest()
            ->paginate(20);

        return view('dashboard.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['is_active'] = true;
        $validated['used_count'] = 0;
        $validated['code'] = strtoupper($validated['code']);

        $shop->coupons()->create($validated);

        return back()->with('success', 'تم إضافة الكوبون بنجاح.');
    }

    public function destroy(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $coupon = $shop->coupons()->findOrFail($id);
        $coupon->delete();

        return back()->with('success', 'تم حذف الكوبون بنجاح.');
    }

    public function toggleActive(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $coupon = $shop->coupons()->findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'تم تحديث حالة الكوبون بنجاح.');
    }
}
