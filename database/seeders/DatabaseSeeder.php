<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Employee;
use App\Models\Coupon;
use App\Models\ShippingCompany;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create merchant user
        $user = User::create([
            'name' => 'تاجر تجريبي',
            'email' => 'test@dzcommerce.com',
            'password' => bcrypt('password'),
        ]);

        // Create shop
        $shop = Shop::create([
            'user_id' => $user->id,
            'name' => 'DZCommerce Store',
            'slug' => 'dzcommerce-store',
            'description' => 'متجر إلكتروني متخصص في المنتجات التقنية',
            'phone' => '0555123456',
            'address' => 'حي 500 مسكن، شارع الاستقلال',
            'wilaya' => 'البليدة',
            'commune' => 'البليدة',
            'default_shipping_cost' => 600,
            'is_active' => true,
        ]);

        // Categories
        $electronics = Category::create(['shop_id' => $shop->id, 'name' => 'إلكترونيات', 'slug' => 'electronics', 'sort_order' => 1]);
        $fashion = Category::create(['shop_id' => $shop->id, 'name' => 'أزياء', 'slug' => 'fashion', 'sort_order' => 2]);
        $accessories = Category::create(['shop_id' => $shop->id, 'name' => 'إكسسوارات', 'slug' => 'accessories', 'sort_order' => 3]);
        $home = Category::create(['shop_id' => $shop->id, 'name' => 'المنزل والمطبخ', 'slug' => 'home-kitchen', 'sort_order' => 4]);

        // Products
        $products = [
            ['name' => 'ساعة ذكية Pro Max Ultra', 'category_id' => $electronics->id, 'sku' => 'SW-001', 'buy_price' => 1800, 'sell_price' => 4500, 'stock_quantity' => 46, 'description' => 'ساعة ذكية متعددة الوظائف مع شاشة AMOLED'],
            ['name' => 'سماعات لاسلكية AirPods Pro', 'category_id' => $electronics->id, 'sku' => 'EP-002', 'buy_price' => 1200, 'sell_price' => 3200, 'stock_quantity' => 65, 'description' => 'سماعات لاسلكية مع إلغاء الضوضاء النشط'],
            ['name' => 'شاحن لاسلكي سريع 15W', 'category_id' => $accessories->id, 'sku' => 'CH-003', 'buy_price' => 400, 'sell_price' => 1200, 'stock_quantity' => 120, 'description' => 'شاحن لاسلكي متوافق مع جميع الأجهزة'],
            ['name' => 'نظارات شمسية بريميوم', 'category_id' => $fashion->id, 'sku' => 'SG-004', 'buy_price' => 600, 'sell_price' => 1800, 'stock_quantity' => 35, 'description' => 'نظارات شمسية عصرية مع حماية UV400'],
            ['name' => 'حافظة هاتف جلد طبيعي', 'category_id' => $accessories->id, 'sku' => 'PC-005', 'buy_price' => 300, 'sell_price' => 900, 'stock_quantity' => 80, 'description' => 'حافظة هاتف من الجلد الطبيعي الفاخر'],
            ['name' => 'سماعة بلوتوث محمولة', 'category_id' => $electronics->id, 'sku' => 'SP-006', 'buy_price' => 800, 'sell_price' => 2200, 'stock_quantity' => 8, 'description' => 'سماعة بلوتوث مقاومة للماء'],
            ['name' => 'قلم ذكي للتابلت', 'category_id' => $electronics->id, 'sku' => 'SP-007', 'buy_price' => 500, 'sell_price' => 1500, 'stock_quantity' => 45, 'description' => 'قلم رقمي دقيق للetablets'],
            ['name' => 'سجادة يوga مقاومة للانزلاق', 'category_id' => $home->id, 'sku' => 'YG-008', 'buy_price' => 700, 'sell_price' => 2000, 'stock_quantity' => 3, 'description' => 'سجادة يوga احترافية بسماكة 6 مم'],
            ['name' => 'حقيبة ظهر رياضية', 'category_id' => $fashion->id, 'sku' => 'BG-009', 'buy_price' => 900, 'sell_price' => 2500, 'stock_quantity' => 25, 'description' => 'حقيبة ظهر متعددة الأغراض مقاومة للماء'],
            ['name' => 'ماكينة قهوة إسبريسو', 'category_id' => $home->id, 'sku' => 'CF-010', 'buy_price' => 3500, 'sell_price' => 7500, 'stock_quantity' => 12, 'description' => 'ماكينة قهوة أوتوماتيكية بضغط 15 بار'],
        ];

        $createdProducts = [];
        foreach ($products as $p) {
            $createdProducts[] = Product::create(array_merge($p, [
                'shop_id' => $shop->id,
                'slug' => Str::slug($p['name']),
                'is_active' => true,
            ]));
        }

        // Orders with different statuses
        $ordersData = [
            ['customer_name' => 'أحمد بن محمد', 'customer_phone' => '0555123456', 'wilaya' => 'البليدة', 'commune' => 'البليدة', 'status' => 'delivered', 'product_idx' => 0, 'qty' => 2],
            ['customer_name' => 'فاطمة الزهراء', 'customer_phone' => '0661789012', 'wilaya' => 'وهران', 'commune' => 'وهران', 'status' => 'delivered', 'product_idx' => 1, 'qty' => 1],
            ['customer_name' => 'يوسف أمين', 'customer_phone' => '0770345678', 'wilaya' => 'قسنطينة', 'commune' => 'قسنطينة', 'status' => 'shipped', 'product_idx' => 0, 'qty' => 1],
            ['customer_name' => 'كريم بوزيد', 'customer_phone' => '0555987654', 'wilaya' => 'الجزائر', 'commune' => 'بئر مراد رايس', 'status' => 'new', 'product_idx' => 2, 'qty' => 3],
            ['customer_name' => 'نورة بن عمر', 'customer_phone' => '0661456789', 'wilaya' => 'عنابة', 'commune' => 'عنابة', 'status' => 'confirmed', 'product_idx' => 3, 'qty' => 1],
            ['customer_name' => 'عبد الرحمن مصطفى', 'customer_phone' => '0770876543', 'wilaya' => 'سطيف', 'commune' => 'سطيف', 'status' => 'processing', 'product_idx' => 4, 'qty' => 2],
            ['customer_name' => 'إيمان بوعلام', 'customer_phone' => '0555234567', 'wilaya' => 'تلمسان', 'commune' => 'تلمسان', 'status' => 'shipped', 'product_idx' => 5, 'qty' => 1],
            ['customer_name' => 'محمد الأمين', 'customer_phone' => '0661654321', 'wilaya' => 'معسكر', 'commune' => 'معسكر', 'status' => 'out_for_delivery', 'product_idx' => 6, 'qty' => 1],
            ['customer_name' => 'سلمى حداد', 'customer_phone' => '0770123987', 'wilaya' => 'تيزي وزو', 'commune' => 'تيزي وزو', 'status' => 'new', 'product_idx' => 7, 'qty' => 1],
            ['customer_name' => 'ياسين بن علي', 'customer_phone' => '0555345678', 'wilaya' => 'بجاية', 'commune' => 'بجاية', 'status' => 'returned', 'product_idx' => 8, 'qty' => 1],
            ['customer_name' => 'هدى مرابط', 'customer_phone' => '0661765432', 'wilaya' => 'ورقلة', 'commune' => 'ورقلة', 'status' => 'delivered', 'product_idx' => 9, 'qty' => 1],
            ['customer_name' => 'عمر بلقاسم', 'customer_phone' => '0770987123', 'wilaya' => 'باتنة', 'commune' => 'باتنة', 'status' => 'cancelled', 'product_idx' => 1, 'qty' => 2],
        ];

        foreach ($ordersData as $i => $od) {
            $product = $createdProducts[$od['product_idx']];
            $subtotal = $product->sell_price * $od['qty'];
            $shipping = 600;
            $total = $subtotal + $shipping;

            $order = Order::create([
                'shop_id' => $shop->id,
                'order_number' => 'ORD-' . str_pad($shop->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad(1001 + $i, 6, '0', STR_PAD_LEFT),
                'customer_name' => $od['customer_name'],
                'customer_phone' => $od['customer_phone'],
                'customer_address' => 'حي النصر، شارع independence',
                'wilaya' => $od['wilaya'],
                'commune' => $od['commune'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'status' => $od['status'],
                'payment_method' => 'cod',
                'payment_status' => $od['status'] === 'delivered' ? 'paid' : 'pending',
                'created_at' => now()->subDays(rand(0, 14))->subHours(rand(0, 23)),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->sell_price,
                'quantity' => $od['qty'],
                'total' => $product->sell_price * $od['qty'],
            ]);

            if (in_array($od['status'], ['shipped', 'delivered'])) {
                $order->update(['shipped_at' => $order->created_at->addDay()]);
            }
            if ($od['status'] === 'delivered') {
                $order->update(['delivered_at' => $order->created_at->addDays(3)]);
            }
        }

        // Employees
        Employee::create(['shop_id' => $shop->id, 'name' => 'يوسف كريم', 'phone' => '0555111222', 'role' => 'manager', 'email' => 'youssef@store.com', 'is_active' => true]);
        Employee::create(['shop_id' => $shop->id, 'name' => 'أمينة بوعلام', 'phone' => '0666333444', 'role' => 'operator', 'email' => 'amina@store.com', 'is_active' => true]);
        Employee::create(['shop_id' => $shop->id, 'name' => 'حسين مراد', 'phone' => '0777555666', 'role' => 'viewer', 'is_active' => false]);

        // Coupons
        Coupon::create(['shop_id' => $shop->id, 'code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'minimum_order' => 3000, 'usage_limit' => 100, 'is_active' => true]);
        Coupon::create(['shop_id' => $shop->id, 'code' => 'FLAT500', 'type' => 'fixed', 'value' => 500, 'minimum_order' => 2000, 'usage_limit' => 50, 'is_active' => true]);

        // Shipping companies
        ShippingCompany::create(['shop_id' => $shop->id, 'name' => 'يالدين', 'slug' => 'yalidine', 'base_cost' => 600, 'per_item_cost' => 100, 'estimated_days' => 3, 'is_active' => true]);
        ShippingCompany::create(['shop_id' => $shop->id, 'name' => 'ZR Express', 'slug' => 'zr-express', 'base_cost' => 550, 'per_item_cost' => 80, 'estimated_days' => 2, 'is_active' => true]);
        ShippingCompany::create(['shop_id' => $shop->id, 'name' => 'ماندايا', 'slug' => 'mandaaya', 'base_cost' => 500, 'per_item_cost' => 100, 'estimated_days' => 4, 'is_active' => false]);

        // Wilaya Shipping Rates (58 wilayas)
        $wilayas = [
            ['01','أدرار',800,600],['02','الشلف',700,500],['03','الأغواط',700,500],['04','أم البواقي',700,500],
            ['05','باتنة',700,500],['06','بجاية',650,450],['07','بسكرة',700,500],['08','بشار',900,700],
            ['09','البليدة',600,400],['10','البويرة',650,450],['11','تمنراست',1000,800],['12','تبسة',800,600],
            ['13','تلمسان',650,450],['14','تيارت',700,500],['15','تيزي وزو',600,400],['16','الجزائر',600,400],
            ['17','الجلفة',750,550],['18','جيجل',650,450],['19','سطيف',650,450],['20','سعيدة',750,550],
            ['21','سكيكدة',700,500],['22','سيدي بلعباس',750,550],['23','عنابة',700,500],['24','قالمة',700,500],
            ['25','قسنطينة',700,500],['26','المدية',650,450],['27','مستغانم',700,500],['28','المسيلة',700,500],
            ['29','معسكر',750,550],['30','ورقلة',850,650],['31','وهران',650,450],['32','البيض',850,650],
            ['33','إليزي',1100,900],['34','برج بوعريريج',700,500],['35','بومرداس',600,400],['36','الطارف',700,500],
            ['37','تندوف',1000,800],['38','تيسمسيلت',700,500],['39','الوادي',750,550],['40','خنشلة',750,550],
            ['41','سوق أهراس',700,500],['42','تيبازة',600,400],['43','ميلة',700,500],['44','عين الدفلى',700,500],
            ['45','النعامة',800,600],['46','عين تموشنت',700,500],['47','غرداية',850,650],['48','غليزان',700,500],
            ['49','تيميمون',900,700],['50','برج باجي مختار',1000,800],['51','أولاد جلال',850,650],['52','بني عباس',950,750],
            ['53','عين صالح',1000,800],['54','عين قزام',1100,900],['55','توقرت',900,700],['56','جانت',1100,900],
            ['57','المغير',850,650],['58','المنيعة',900,700],
        ];
        foreach ($wilayas as [$code, $name, $dom, $sd]) {
            \App\Models\WilayaShippingRate::create([
                'shop_id' => $shop->id,
                'wilaya_code' => $code,
                'wilaya_name' => $name,
                'domicile_cost' => $dom,
                'stop_desk_cost' => $sd,
                'is_active' => true,
            ]);
        }

        // Settings
        Setting::set($shop->id, 'currency', 'DZD');
        Setting::set($shop->id, 'currency_symbol', 'DA');
        Setting::set($shop->id, 'store_name', 'DZCommerce Store');
        Setting::set($shop->id, 'store_description', 'متجر إلكتروني متخصص في المنتجات التقنية');
        Setting::set($shop->id, 'telegram_bot_token', '');
        Setting::set($shop->id, 'telegram_chat_id', '');
        Setting::set($shop->id, 'telegram_enabled', '0');
        Setting::set($shop->id, 'dhd_api_url', 'https://platform.dhd-dz.com');
        Setting::set($shop->id, 'dhd_token', 'wTF3fM6GazHAdbHt37TVR8ddsa8Qbxg3YgoCNBDTy896mqNWXW1TiOSdIQ6r');
        Setting::set($shop->id, 'dhd_default_weight', '1000');
        Setting::set($shop->id, 'dhd_can_open', '1');
        Setting::set($shop->id, 'dhd_is_fragile', '0');
        Setting::set($shop->id, 'dhd_auto_send', '0');

        echo "Seed completed! Test login: test@dzcommerce.com / password\n";
    }
}
