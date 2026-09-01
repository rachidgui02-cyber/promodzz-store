<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $shippingCompanies = $shop->shippingCompanies()->get();

        $pendingShipments = $shop->orders()
            ->whereIn('status', ['new', 'confirmed'])
            ->whereNull('tracking_number')
            ->latest()
            ->paginate(20);

        $ordersByStatus = [
            'pending' => $pendingShipments,
            'confirmed' => $shop->orders()
                ->where('status', 'confirmed')
                ->whereNotNull('tracking_number')
                ->latest()
                ->paginate(10, ['*'], 'confirmed_page'),
            'shipped' => $shop->orders()
                ->where('status', 'shipped')
                ->latest()
                ->paginate(10, ['*'], 'shipped_page'),
            'delivered' => $shop->orders()
                ->where('status', 'delivered')
                ->latest()
                ->paginate(10, ['*'], 'delivered_page'),
        ];

        $dhdConfig = [
            'token' => \App\Models\Setting::get($shop->id, 'dhd_token', ''),
            'auto_send' => \App\Models\Setting::get($shop->id, 'dhd_auto_send', '0'),
            'default_weight' => \App\Models\Setting::get($shop->id, 'dhd_default_weight', '1000'),
            'can_open' => \App\Models\Setting::get($shop->id, 'dhd_can_open', '1'),
            'is_fragile' => \App\Models\Setting::get($shop->id, 'dhd_is_fragile', '0'),
        ];

        return view('dashboard.shipping.index', [
            'shop' => $shop,
            'companies' => $shippingCompanies,
            'ordersByStatus' => $ordersByStatus,
            'pendingShipments' => $ordersByStatus['pending'],
            'dhdConfig' => $dhdConfig,
        ]);
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'base_cost' => ['nullable', 'numeric', 'min:0'],
            'per_item_cost' => ['nullable', 'numeric', 'min:0'],
            'estimated_days' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $shop->shippingCompanies()->create($validated);

        return back()->with('success', 'تمت إضافة شركة الشحن بنجاح.');
    }

    public function toggleActive(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $company = $shop->shippingCompanies()->findOrFail($id);
        $company->update(['is_active' => !$company->is_active]);

        return back()->with('success', 'تم تحديث حالة شركة الشحن.');
    }

    public function updateCompany(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $company = $shop->shippingCompanies()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'base_cost' => ['nullable', 'numeric', 'min:0'],
            'per_item_cost' => ['nullable', 'numeric', 'min:0'],
            'estimated_days' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $company->update($validated);

        return back()->with('success', 'تم تحديث شركة الشحن بنجاح.');
    }
}
