<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $settings = $shop->settings()
            ->get()
            ->mapWithKeys(fn ($s) => [$s->key => $s->value]);

        return view('dashboard.settings.index', compact('shop', 'settings'));
    }

    public function update(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'wilaya' => ['nullable', 'string', 'max:100'],
            'commune' => ['nullable', 'string', 'max:100'],
            'default_shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:255'],
            'access_token' => ['nullable', 'string', 'max:500'],
            'api_version' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'dhd_api_url' => ['nullable', 'url', 'max:500'],
            'dhd_token' => ['nullable', 'string', 'max:500'],
            'dhd_default_weight' => ['nullable', 'integer', 'min:100', 'max:50000'],
            'dhd_can_open' => ['nullable'],
            'dhd_is_fragile' => ['nullable'],
            'dhd_auto_send' => ['nullable'],
            'telegram_bot_token' => ['nullable', 'string', 'max:500'],
            'telegram_chat_id' => ['nullable', 'string', 'max:100'],
            'telegram_enabled' => ['nullable'],
        ]);

        $shopFields = collect($validated)->only([
            'name', 'description', 'phone', 'address',
            'wilaya', 'commune', 'default_shipping_cost',
            'facebook_pixel_id', 'access_token', 'api_version',
        ])->filter()->toArray();

        if ($request->hasFile('logo')) {
            $shopFields['logo'] = $request->file('logo')->store('shops', 'public');
        }

        if (!empty($shopFields['name']) && empty($shop->slug)) {
            $shopFields['slug'] = Str::slug($shopFields['name']) . '-' . $shop->id;
        }

        $shop->update($shopFields);

        \App\Models\Setting::set($shop->id, 'dhd_api_url', $request->input('dhd_api_url', ''));
        \App\Models\Setting::set($shop->id, 'dhd_token', $request->input('dhd_token', ''));
        \App\Models\Setting::set($shop->id, 'dhd_default_weight', $request->input('dhd_default_weight', '1000'));
        \App\Models\Setting::set($shop->id, 'dhd_can_open', $request->boolean('dhd_can_open') ? '1' : '0');
        \App\Models\Setting::set($shop->id, 'dhd_is_fragile', $request->boolean('dhd_is_fragile') ? '1' : '0');
        \App\Models\Setting::set($shop->id, 'dhd_auto_send', $request->boolean('dhd_auto_send') ? '1' : '0');

        \App\Models\Setting::set($shop->id, 'telegram_bot_token', $request->input('telegram_bot_token', ''));
        \App\Models\Setting::set($shop->id, 'telegram_chat_id', $request->input('telegram_chat_id', ''));
        \App\Models\Setting::set($shop->id, 'telegram_enabled', $request->boolean('telegram_enabled') ? '1' : '0');

        return back()->with('success', 'تم تحديث الإعدادات بنجاح.');
    }

    public function wilayaRates(Request $request)
    {
        $shop = $request->user()->shop;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'rates' => 'required|array',
                'rates.*.wilaya_code' => 'required|string',
                'rates.*.stop_desk' => 'required|numeric|min:0',
                'rates.*.home' => 'required|numeric|min:0',
            ]);

            foreach ($validated['rates'] as $rate) {
                $wilayaName = \App\Models\WilayaShippingRate::where('wilaya_code', $rate['wilaya_code'])->value('wilaya_name');
                $shop->wilayaRates()->updateOrCreate(
                    ['wilaya_code' => $rate['wilaya_code']],
                    [
                        'wilaya_name' => $wilayaName ?? $rate['wilaya_code'],
                        'stop_desk_cost' => $rate['stop_desk'],
                        'domicile_cost' => $rate['home'],
                    ]
                );
            }

            return back()->with('success', 'تم تحديث أسعار الشحن بنجاح.');
        }

        $wilayaRates = $shop->wilayaRates()->orderBy('wilaya_code')->get();

        return view('dashboard.settings.wilaya_shipping', compact('shop', 'wilayaRates'));
    }

    public function updateFacebook(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'facebook_pixel_id' => ['nullable', 'string', 'max:255'],
            'access_token' => ['nullable', 'string', 'max:500'],
            'api_version' => ['nullable', 'string', 'max:10'],
        ]);

        $shop->update($validated);

        return back()->with('success', 'تم تحديث إعدادات فيسبوك بنجاح.');
    }

    public function testDhd(Request $request)
    {
        $shop = $request->user()->shop;
        $service = new \App\Services\DhdShippingService($shop->id);
        $result = $service->testConnection();
        return response()->json($result);
    }

    public function testTelegram(Request $request)
    {
        $shop = $request->user()->shop;
        \App\Models\Setting::set($shop->id, 'telegram_bot_token', $request->input('token', ''));
        \App\Models\Setting::set($shop->id, 'telegram_chat_id', $request->input('chat_id', ''));

        $tg = new \App\Services\TelegramNotificationService($shop->id);
        $result = $tg->testConnection();

        if ($result['success']) {
            $tg->sendMessage("✅ <b>تم الاتصال بنجاح!</b>\n\nهذا اختبار من DZCommerce Store");
        }

        return response()->json($result);
    }
}
