<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    private array $wilayas = [
        '01' => 'أدرار','02' => 'الشلف','03' => 'الأغواط','04' => 'أم البواقي','05' => 'باتنة',
        '06' => 'بجاية','07' => 'بسكرة','08' => 'بشار','09' => 'البليدة','10' => 'البويرة',
        '11' => 'تمنراست','12' => 'تبسة','13' => 'تلمسان','14' => 'تيارت','15' => 'تيزي وزو',
        '16' => 'الجزائر','17' => 'الجلفة','18' => 'جيجل','19' => 'سطيف','20' => 'سعيدة',
        '21' => 'سكيكدة','22' => 'سيدي بلعباس','23' => 'عنابة','24' => 'قالمة','25' => 'قسنطينة',
        '26' => 'المدية','27' => 'مستغانم','28' => 'المسيلة','29' => 'معسكر','30' => 'ورقلة',
        '31' => 'وهران','32' => 'البيض','33' => 'إليزي','34' => 'برج بوعريريج','35' => 'بومرداس',
        '36' => 'الطارف','37' => 'تندوف','38' => 'تيسمسيلت','39' => 'الوادي','40' => 'خنشلة',
        '41' => 'سوق أهراس','42' => 'تيبازة','43' => 'ميلة','44' => 'عين الدفلى','45' => 'النعامة',
        '46' => 'عين تموشنت','47' => 'غرداية','48' => 'غليزان','49' => 'تيميمون','50' => 'برج باجي مختار',
        '51' => 'أولاد جلال','52' => 'بني عباس','53' => 'عين صالح','54' => 'عين قزام','55' => 'توقرت',
        '56' => 'جانت','57' => 'المغير','58' => 'المنيعة',
    ];

    public function show($slug)
    {
        $shop = Shop::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $query = $shop->products()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->with('category');

        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('storefront.show', compact('shop', 'products'));
    }

    public function showProduct($slug, $productId)
    {
        $product = Product::where('id', $productId)
            ->where('shop_id', function ($query) use ($slug) {
                $query->select('id')->from('shops')->where('slug', $slug);
            })
            ->where('is_active', true)
            ->firstOrFail();

        $shop = $product->shop;
        $relatedProducts = $product->shop->products()
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $shippingCost = $shop->default_shipping_cost ?? 600;

        return view('storefront.product', compact('product', 'shop', 'relatedProducts', 'shippingCost'));
    }

    public function submitOrder(Request $request, $slug)
    {
        $shop = Shop::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^[0-9]{10}$/',
            'wilaya' => 'required|string|max:100',
            'commune' => 'required|string|max:100',
            'delivery_type' => 'required|in:stop_desk,home',
            'stop_desk_commune' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::where('id', $validated['product_id'])
            ->where('shop_id', $shop->id)
            ->where('is_active', true)
            ->firstOrFail();

        if ($product->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'الكمية المتوفرة غير كافية. المتوفر: ' . $product->stock_quantity])->withInput();
        }

        $qty = $validated['quantity'];
        $subtotal = $product->sell_price * $qty;
        $shipping = \App\Models\WilayaShippingRate::getCostForWilaya($shop->id, $validated['wilaya'], $validated['delivery_type']);
        $total = $subtotal + $shipping;

        $address = $validated['delivery_type'] === 'stop_desk'
            ? 'مكتب التوصيل: ' . ($validated['stop_desk_commune'] ?? $validated['commune']) . ' - ' . $validated['wilaya']
            : $validated['wilaya'] . ' - ' . $validated['commune'];

        $orderNumber = Order::generateOrderNumber();

        $order = Order::create([
            'shop_id' => $shop->id,
            'order_number' => $orderNumber,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $address,
            'wilaya' => $validated['wilaya'],
            'commune' => $validated['commune'],
            'notes' => 'نوع التوصيل: ' . ($validated['delivery_type'] === 'stop_desk' ? 'المكتب - ' . ($validated['stop_desk_commune'] ?? '') : 'المنزل'),
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $total,
            'status' => 'new',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->sell_price,
            'quantity' => $qty,
            'total' => $product->sell_price * $qty,
        ]);

        if (\App\Models\Setting::get($shop->id, 'telegram_enabled', '0') === '1') {
            $tg = new \App\Services\TelegramNotificationService($shop->id);
            $tg->sendNewOrderNotification($order);
        }

        return redirect()->route('storefront.success', [$slug, $order->id]);
    }

    public function success($slug, $orderId)
    {
        $shop = Shop::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $order = $shop->orders()->where('id', $orderId)->firstOrFail();

        return view('storefront.success', ['order' => $order, 'slug' => $slug]);
    }
}
