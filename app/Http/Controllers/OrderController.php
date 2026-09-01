<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $query = $shop->orders()->with('items.product');

        $currentStatus = $request->status ?? null;
        $workflow = $request->workflow ?? null;
        $dateFilter = $request->date ?? null;
        $search = $request->search ?? null;
        $sourceFilter = $request->source ?? null;
        $wilayaFilter = $request->wilaya ?? null;

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('workflow')) {
            match ($workflow) {
                'new' => $query->where('status', 'new'),
                'to_call' => $query->whereIn('status', ['new', 'waiting_callback']),
                'no_answer' => $query->whereIn('status', ['no_answer_1', 'no_answer_2', 'no_answer_3', 'customer_unavailable']),
                'confirmed' => $query->where('status', 'confirmed'),
                'in_transit' => $query->whereIn('status', ['processing', 'shipped', 'out_for_delivery']),
                'delivered' => $query->where('status', 'delivered'),
                'returned' => $query->where('status', 'returned'),
                'cancelled' => $query->where('status', 'cancelled'),
                default => null,
            };
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($dateFilter);
            $query->whereDate('created_at', $date);
        }

        if ($request->filled('source')) {
            $query->where('source', $sourceFilter);
        }

        if ($request->filled('wilaya')) {
            $query->where('wilaya', $wilayaFilter);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $allOrders = $shop->orders();
        $statusCounts = [
            'all' => (clone $allOrders)->count(),
            'new' => (clone $allOrders)->where('status', 'new')->count(),
            'to_call' => (clone $allOrders)->whereIn('status', ['new', 'waiting_callback'])->count(),
            'no_answer' => (clone $allOrders)->whereIn('status', ['no_answer_1', 'no_answer_2', 'no_answer_3', 'customer_unavailable'])->count(),
            'confirmed' => (clone $allOrders)->where('status', 'confirmed')->count(),
            'in_transit' => (clone $allOrders)->whereIn('status', ['processing', 'shipped', 'out_for_delivery'])->count(),
            'delivered' => (clone $allOrders)->where('status', 'delivered')->count(),
            'returned' => (clone $allOrders)->where('status', 'returned')->count(),
            'cancelled' => (clone $allOrders)->where('status', 'cancelled')->count(),
        ];

        $pendingSyncCount = (clone $allOrders)
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'out_for_delivery'])
            ->whereNotNull('tracking_number')
            ->count();

        $totalOrders = (clone $allOrders)->count();

        $shippingCompanies = $shop->shippingCompanies()->where('is_active', true)->get();

        $wilayasList = [
            '01'=>'أدرار','02'=>'الشلف','03'=>'الأغواط','04'=>'أم البواقي','05'=>'باتنة',
            '06'=>'بجاية','07'=>'بسكرة','08'=>'بشار','09'=>'البليدة','10'=>'البويرة',
            '11'=>'تمنراست','12'=>'تبسة','13'=>'تلمسان','14'=>'تيارت','15'=>'تيزي وزو',
            '16'=>'الجزائر','17'=>'الجلفة','18'=>'جيجل','19'=>'سطيف','20'=>'سعيدة',
            '21'=>'سكيكدة','22'=>'سيدي بلعباس','23'=>'عنابة','24'=>'قالمة','25'=>'قسنطينة',
            '26'=>'المدية','27'=>'مستغانم','28'=>'المسيلة','29'=>'معسكر','30'=>'ورقلة',
            '31'=>'وهران','32'=>'البيض','33'=>'إليزي','34'=>'برج بوعريريج','35'=>'بومرداس',
            '36'=>'الطارف','37'=>'تندوف','38'=>'تيسمسيلت','39'=>'الوادي','40'=>'خنشلة',
            '41'=>'سوق أهراس','42'=>'تيبازة','43'=>'ميلة','44'=>'عين الدفلى','45'=>'النعامة',
            '46'=>'عين تموشنت','47'=>'غرداية','48'=>'غليزان','49'=>'تيميمون','50'=>'برج باجي مختار',
            '51'=>'أولاد جلال','52'=>'بني عباس','53'=>'عين صالح','54'=>'عين قزام','55'=>'توقرت',
            '56'=>'جانت','57'=>'المغير','58'=>'المنيعة',
        ];

        return view('dashboard.orders.index', compact(
            'orders', 'statusCounts', 'currentStatus', 'search', 'workflow',
            'dateFilter', 'pendingSyncCount', 'totalOrders', 'shippingCompanies',
            'wilayasList', 'sourceFilter', 'wilayaFilter'
        ));
    }

    public function show(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $order = $shop->orders()
            ->with(['items.product', 'statusHistories'])
            ->findOrFail($id);

        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $order = $shop->orders()->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:new,confirmed,waiting_callback,customer_unavailable,no_answer_1,no_answer_2,no_answer_3,processing,shipped,out_for_delivery,delivered,returned,cancelled'],
        ]);

        $newStatus = $validated['status'];

        if (in_array($newStatus, Order::CALL_FLOW_STATUSES)) {
            $order->increment('call_attempts');
            $order->update(['last_call_at' => now()]);
        }

        $oldStatus = $order->status;
        $order->updateStatus($newStatus);

        if (\App\Models\Setting::get($shop->id, 'telegram_enabled', '0') === '1') {
            $tg = new \App\Services\TelegramNotificationService($shop->id);
            $tg->sendStatusChangeNotification($order, $oldStatus, $newStatus);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحالة بنجاح',
                'new_status' => $newStatus,
                'label' => Order::getStatusLabel($newStatus),
            ]);
        }

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }

    public function incrementCallAttempt(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $order = $shop->orders()->findOrFail($id);

        $order->increment('call_attempts');
        $order->update(['last_call_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'call_attempts' => $order->call_attempts,
                'message' => "محاولة #{$order->call_attempts} مسجلة",
            ]);
        }

        return back()->with('success', "تم تسجيل محاولة الاتصال رقم {$order->call_attempts}");
    }

    public function quickUpdate(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $order = $shop->orders()->findOrFail($id);

        $validated = $request->validate([
            'wilaya' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:50',
        ]);

        $order->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم التحديث بنجاح',
            ]);
        }

        return back()->with('success', 'تم تحديث الطلب بنجاح.');
    }

    public function syncOrders(Request $request)
    {
        $shop = $request->user()->shop;
        $results = ['updated' => 0, 'unchanged' => 0, 'details' => []];

        $dhdToken = \App\Models\Setting::get($shop->id, 'dhd_token', '');
        if (!empty($dhdToken)) {
            $dhd = new \App\Services\DhdShippingService($shop->id);
            $dhdOrders = $shop->orders()
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'out_for_delivery'])
                ->where('shipping_company', 'DHD Livraison')
                ->whereNotNull('tracking_number')
                ->get();

            $orderNumbers = $dhdOrders->pluck('order_number')->toArray();
            if (!empty($orderNumbers)) {
                $syncResult = $dhd->trackParcels($orderNumbers);
                if ($syncResult['success']) {
                    foreach ($syncResult['results'] as $item) {
                        $order = $dhdOrders->firstWhere('order_number', $item['order_number']);
                        if (!$order || !$item['new_status']) {
                            $results['unchanged']++;
                            continue;
                        }
                        $newStatus = $item['new_status'];
                        $order->updateStatus($newStatus);
                        $results['updated']++;
                        $results['details'][] = [
                            'order' => $order->order_number,
                            'from' => $item['status'] ?? 'unknown',
                            'to' => $newStatus,
                            'label' => Order::getStatusLabel($newStatus),
                            'color' => Order::getStatusColor($newStatus),
                        ];
                    }
                }
            }
        }

        $api = new \App\Services\ShippingApiService();
        $shippedOrders = $shop->orders()
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'out_for_delivery'])
            ->where('shipping_company', '!=', 'DHD Livraison')
            ->get();

        foreach ($shippedOrders as $order) {
            $company = $shop->shippingCompanies()->where('slug', $order->shipping_company)->first();
            if (!$company) continue;

            $syncResult = $api->syncOrderStatus($order, $company);
            if ($syncResult['success'] && $syncResult['changed']) {
                $newStatus = $syncResult['new_status'];
                $order->updateStatus($newStatus);
                $results['updated']++;
                $results['details'][] = [
                    'order' => $order->order_number,
                    'from' => $syncResult['old_status'],
                    'to' => $newStatus,
                    'label' => Order::getStatusLabel($newStatus),
                    'color' => Order::getStatusColor($newStatus),
                ];
            } else {
                $results['unchanged']++;
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "تمت المزامنة: {$results['updated']} طلب تم تحديثه، {$results['unchanged']} بدون تغيير",
                'updated' => $results['updated'],
                'unchanged' => $results['unchanged'],
                'details' => $results['details'],
            ]);
        }

        return back()->with('success', "تمت المزامنة: {$results['updated']} طلب تم تحديثه، {$results['unchanged']} بدون تغيير");
    }

    public function sendAllToShipping(Request $request)
    {
        $shop = $request->user()->shop;
        $pendingOrders = $shop->orders()
            ->whereIn('status', ['new', 'confirmed'])
            ->whereNull('tracking_number')
            ->get();

        if ($pendingOrders->isEmpty()) {
            return back()->with('error', 'لا توجد طلبات جديدة أو مؤكدة للإرسال.');
        }

        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($pendingOrders as $order) {
            $trackingSet = false;

            $dhdToken = \App\Models\Setting::get($shop->id, 'dhd_token', '');

            if (!empty($dhdToken)) {
                $dhd = new \App\Services\DhdShippingService($shop->id);
                $result = $dhd->createParcel($order);
                if ($result['success']) {
                    $order->update([
                        'tracking_number' => $result['tracking'],
                        'shipping_company' => 'DHD Livraison',
                    ]);
                    $trackingSet = true;
                } else {
                    $errors[] = $order->order_number . ': ' . ($result['message'] ?? 'فشل إنشاء الطرد');
                }
            } else {
                $activeCompany = $order->shop->shippingCompanies()->where('is_active', true)->first();
                if ($activeCompany) {
                    $api = new \App\Services\ShippingApiService();
                    $result = $api->createShipment($order, $activeCompany);
                    if ($result['success']) {
                        $order->update([
                            'tracking_number' => $result['tracking_number'],
                            'shipping_company' => $activeCompany->name,
                        ]);
                        $trackingSet = true;
                    } else {
                        $errors[] = $order->order_number . ': ' . ($result['message'] ?? 'فشل الشحن');
                    }
                } else {
                    $errors[] = $order->order_number . ': لا توجد شركة شحن نشطة';
                }
            }

            if ($trackingSet) {
                if ($order->status === 'new') {
                    $order->updateStatus('confirmed');
                }

                if (\App\Models\Setting::get($shop->id, 'telegram_enabled', '0') === '1') {
                    $tg = new \App\Services\TelegramNotificationService($shop->id);
                    $tg->sendStatusChangeNotification($order, $order->status, 'shipped');
                }

                $sent++;
            } else {
                $failed++;
            }
        }

        $msg = "تم إرسال {$sent} طلب للشحن بنجاح.";
        if ($failed > 0) {
            $msg .= " {$failed} طلب فشل.";
            if (!empty($errors)) {
                $msg .= "\n" . implode("\n", $errors);
            }
        }

        return back()->with($sent > 0 ? 'success' : 'error', $msg);
    }

    public function printLabel(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $order = $shop->orders()->findOrFail($id);

        return view('dashboard.orders.label', compact('order'));
    }

    public function sendToShipping(Request $request, $id)
    {
        $shop = $request->user()->shop;
        $order = $shop->orders()->findOrFail($id);

        if ($order->tracking_number) {
            return back()->with('error', 'الطلب له رقم تتبع بالفعل.');
        }

        $result = $this->sendOrderToShipping($order, $shop);

        if ($result['success']) {
            return back()->with('success', "تم إرسال الطلب للشحن بنجاح. رقم التتبع: {$result['tracking']}");
        }

        return back()->with('error', $result['message'] ?? 'فشل إرسال الطلب للشحن.');
    }

    public function importExcel(Request $request)
    {
        return view('dashboard.orders.import');
    }

    public function processImport(Request $request)
    {
        $shop = $request->user()->shop;

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
            'send_to_shipping' => ['nullable', 'boolean'],
            'format' => ['nullable', 'string', 'in:youcan,dhd'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');

        $imported = 0;
        $sentToShipping = 0;
        $failed = 0;
        $errors = [];
        $importedOrders = [];

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'الملف فارغ أو غير صالح.']);
        }

        $headers = array_map(function($h) {
            return trim(mb_convert_encoding($h, 'UTF-8', 'UTF-8'));
        }, $headers);

        $format = $request->input('format', 'youcan');

        if ($format === 'dhd') {
            $headerMap = [
                'reference commande' => 'order_number',
                'nom et prenom du destinataire*' => 'customer_name',
                'telephone*' => 'customer_phone',
                'telephone 2' => 'customer_phone2',
                'code wilaya*' => 'wilaya_code',
                'wilaya de livraison' => 'wilaya',
                'commune de livraison*' => 'commune',
                'adresse de livraison*' => 'customer_address',
                'produit*' => 'product_name',
                'poids (kg)' => 'weight',
                'montant du colis*' => 'total',
                'remarque' => 'notes',
                'FRAGILE' => 'fragile',
                'ESSAYAGE PERMI' => 'essayage',
                'ECHANGE' => 'echange',
                'PICK UP' => 'pick_up',
                'RECOUVREMENT' => 'recouvrement',
                'STOP DESK' => 'stop_desk',
            ];
        } else {
            $headerMap = [
                'تم الإنشاء في' => 'created_at',
                'عنوان المنتج' => 'product_name',
                'اسم العميل' => 'customer_name',
                'رقم الهاتف' => 'customer_phone',
                'ولاية الشحن' => 'wilaya',
                'مدينة الشحن' => 'commune',
                'الكمية' => 'qty',
                'سعر الوحدة' => 'price',
                'تكلفة الشحن' => 'shipping_cost',
                'السعر الإجمالي' => 'total',
                'ملاحظة العميل' => 'notes',
                'customer_name' => 'customer_name',
                'customer_phone' => 'customer_phone',
                'customer_address' => 'customer_address',
                'wilaya' => 'wilaya',
                'commune' => 'commune',
                'qty' => 'qty',
                'quantity' => 'quantity',
                'product_name' => 'product_name',
                'product' => 'product_name',
                'price' => 'price',
                'subtotal' => 'subtotal',
                'total' => 'total',
                'shipping_cost' => 'shipping_cost',
                'notes' => 'notes',
                'remark' => 'notes',
                'discount' => 'discount',
                'payment_method' => 'payment_method',
                'payment_status' => 'payment_status',
            ];
        }

        $mappedHeaders = [];
        foreach ($headers as $header) {
            $mappedHeaders[] = $headerMap[$header] ?? $header;
        }

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $data = array_combine($mappedHeaders, $row);

                DB::beginTransaction();

                if ($format === 'dhd') {
                    $wilayaCode = (int) ($data['wilaya_code'] ?? 0);
                    $wilayaName = $data['wilaya'] ?? '';
                    $total = (float) ($data['total'] ?? 0);

                    $order = $shop->orders()->create([
                        'order_number' => $data['order_number'] ?: Order::generateOrderNumber(),
                        'customer_name' => $data['customer_name'] ?? '',
                        'customer_phone' => $data['customer_phone'] ?? '',
                        'customer_address' => $data['customer_address'] ?? '',
                        'wilaya' => $wilayaName,
                        'commune' => $data['commune'] ?? '',
                        'notes' => $data['notes'] ?? '',
                        'status' => 'new',
                        'payment_status' => 'pending',
                        'payment_method' => 'cod',
                        'subtotal' => $total,
                        'shipping_cost' => 0,
                        'discount' => 0,
                        'total' => $total,
                    ]);

                    $productName = $data['product_name'] ?? null;
                    if ($productName) {
                        $order->items()->create([
                            'product_id' => null,
                            'product_name' => $productName,
                            'quantity' => 1,
                            'product_price' => $total,
                            'total' => $total,
                        ]);
                    }

                    $notes = $data['notes'] ?? '';
                    if (($data['fragile'] ?? '') === 'OUI') $notes .= ' [هش]';
                    if (($data['stop_desk'] ?? '') === 'OUI') $notes .= ' [مكتب]';
                    if (($data['recouvrement'] ?? '') === 'OUI') $notes .= ' [تحصيل]';

                    if ($notes !== $data['notes'] ?? '') {
                        $order->update(['notes' => trim($notes)]);
                    }

                } else {
                    $productPrice = (float) ($data['price'] ?? 0);
                    $quantity = (int) ($data['qty'] ?? $data['quantity'] ?? 1);
                    $shippingCost = (float) ($data['shipping_cost'] ?? $shop->default_shipping_cost ?? 600);
                    $total = (float) ($data['total'] ?? ($productPrice * $quantity + $shippingCost));

                    $order = $shop->orders()->create([
                        'order_number' => Order::generateOrderNumber(),
                        'customer_name' => $data['customer_name'] ?? '',
                        'customer_phone' => $data['customer_phone'] ?? '',
                        'customer_address' => $data['commune'] ?? $data['customer_address'] ?? '',
                        'wilaya' => $data['wilaya'] ?? '',
                        'commune' => $data['commune'] ?? '',
                        'notes' => $data['notes'] ?? '',
                        'status' => 'new',
                        'payment_status' => $data['payment_status'] ?? 'pending',
                        'payment_method' => $data['payment_method'] ?? 'cod',
                        'subtotal' => $productPrice * $quantity,
                        'shipping_cost' => $shippingCost,
                        'discount' => (float) ($data['discount'] ?? 0),
                        'total' => $total,
                    ]);

                    $productName = $data['product_name'] ?? null;
                    if ($productName) {
                        $order->items()->create([
                            'product_id' => null,
                            'product_name' => $productName,
                            'quantity' => $quantity,
                            'product_price' => $productPrice,
                            'total' => $productPrice * $quantity,
                        ]);
                    }
                }

                $order->statusHistories()->create([
                    'status' => 'new',
                    'notes' => 'تم الإنشاء عبر استيراد ملف CSV',
                ]);

                DB::commit();
                $importedOrders[] = $order;
                $imported++;
            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                $errors[] = "صف " . ($imported + $failed + 1) . ": " . $e->getMessage();
            }
        }

        fclose($handle);

        if ($request->boolean('send_to_shipping') && !empty($importedOrders)) {
            foreach ($importedOrders as $order) {
                try {
                    $result = $this->sendOrderToShipping($order, $shop);
                    if ($result['success']) {
                        $sentToShipping++;
                    } else {
                        $errors[] = "{$order->order_number}: " . ($result['message'] ?? 'فشل الشحن');
                    }
                } catch (\Exception $e) {
                    $errors[] = "{$order->order_number}: " . $e->getMessage();
                }
            }
        }

        $message = "تم استيراد {$imported} طلب بنجاح.";
        if ($sentToShipping > 0) {
            $message .= " {$sentToShipping} طلب تم إرسالها للشحن.";
        }
        if ($failed > 0) {
            $message .= " {$failed} طلب فشل.";
        }

        return back()->with('import_result', [
            'imported' => $imported,
            'sent_to_shipping' => $sentToShipping,
            'failed' => $failed,
            'errors' => $errors,
            'message' => $message,
        ]);
    }

    public function exportDhd(Request $request)
    {
        $shop = $request->user()->shop;

        $orders = $shop->orders()
            ->whereIn('status', ['new', 'confirmed'])
            ->whereNull('tracking_number')
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'لا توجد طلبيات للتصدير.');
        }

        $headers = [
            'reference commande',
            'nom et prenom du destinataire*',
            'telephone*',
            'telephone 2',
            'code wilaya*',
            'wilaya de livraison',
            'commune de livraison*',
            'adresse de livraison*',
            'produit*',
            'poids (kg)',
            'montant du colis*',
            'remarque',
            'FRAGILE',
            'ESSAYAGE PERMI',
            'ECHANGE',
            'PICK UP',
            'RECOUVREMENT',
            'STOP DESK',
            'Lien map',
        ];

        $callback = function () use ($orders, $headers, $shop) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers);

            foreach ($orders as $order) {
                $wilayaCode = $this->getWilayaCode($order->wilaya);
                $productDesc = $order->items->pluck('product_name')->implode(', ') ?: 'منتج';

                $row = [
                    $order->order_number,
                    $order->customer_name,
                    preg_replace('/[^0-9]/', '', $order->customer_phone),
                    '',
                    $wilayaCode,
                    $order->wilaya,
                    $order->commune,
                    $order->customer_address ?: $order->commune,
                    $productDesc,
                    1,
                    $order->total,
                    $order->notes ?? '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        $filename = 'dhd_orders_' . now()->format('Y-m-d_His') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    protected function getWilayaCode(string $wilayaName): int
    {
        $wilayas = [
            'أدرار' => 1, 'الشلف' => 2, 'الأغواط' => 3, 'أم البواقي' => 4, 'باتنة' => 5,
            'بجاية' => 6, 'بسكرة' => 7, 'بشار' => 8, 'البليدة' => 9, 'البويرة' => 10,
            'تمنراست' => 11, 'تبسة' => 12, 'تلمسان' => 13, 'تيارت' => 14, 'تيزي وزو' => 15,
            'الجزائر' => 16, 'الجلفة' => 17, 'جيجل' => 18, 'سطيف' => 19, 'سعيدة' => 20,
            'سكيكدة' => 21, 'سيدي بلعباس' => 22, 'عنابة' => 23, 'قالمة' => 24, 'قسنطينة' => 25,
            'المدية' => 26, 'مستغانم' => 27, 'المسيلة' => 28, 'معسكر' => 29, 'ورقلة' => 30,
            'وهران' => 31, 'البيض' => 32, 'إليزي' => 33, 'برج بوعريريج' => 34, 'بومرداس' => 35,
            'الطارف' => 36, 'تندوف' => 37, 'تيسمسيلت' => 38, 'الوادي' => 39, 'خنشلة' => 40,
            'سوق أهراس' => 41, 'تيبازة' => 42, 'ميلة' => 43, 'عين الدفلى' => 44, 'النعامة' => 45,
            'عين تموشنت' => 46, 'غرداية' => 47, 'غليزان' => 48, 'تيميمون' => 49,
            'برج باجي مختار' => 50, 'أولاد جلال' => 51, 'بني عباس' => 52, 'عين صالح' => 53,
            'عين قزام' => 54, 'توقرت' => 55, 'جانت' => 56, 'المغير' => 57, 'المنيعة' => 58,
        ];

        return $wilayas[$wilayaName] ?? 16;
    }

    protected function sendOrderToShipping(Order $order, $shop): array
    {
        $dhdToken = \App\Models\Setting::get($shop->id, 'dhd_token', '');

        if (!empty($dhdToken)) {
            $dhd = new \App\Services\DhdShippingService($shop->id);
            $result = $dhd->createParcel($order);

            if ($result['success']) {
                $order->update([
                    'tracking_number' => $result['tracking'],
                    'shipping_company' => 'DHD Livraison',
                ]);

                if ($order->status === 'new') {
                    $order->updateStatus('confirmed');
                }

                if (\App\Models\Setting::get($shop->id, 'telegram_enabled', '0') === '1') {
                    $tg = new \App\Services\TelegramNotificationService($shop->id);
                    $tg->sendStatusChangeNotification($order, $order->status, 'shipped');
                }

                return ['success' => true, 'tracking' => $result['tracking']];
            }

            return $result;
        }

        $activeCompany = $shop->shippingCompanies()->where('is_active', true)->first();
        if ($activeCompany) {
            $api = new \App\Services\ShippingApiService();
            $result = $api->createShipment($order, $activeCompany);

            if ($result['success']) {
                $order->update([
                    'tracking_number' => $result['tracking_number'],
                    'shipping_company' => $activeCompany->name,
                ]);

                if ($order->status === 'new') {
                    $order->updateStatus('confirmed');
                }

                return ['success' => true, 'tracking' => $result['tracking_number']];
            }

            return $result;
        }

        return ['success' => false, 'message' => 'لا توجد شركة شحن نشطة'];
    }
}
