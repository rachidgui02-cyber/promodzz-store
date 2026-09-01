<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم الطلب بنجاح - {{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { cairo: ['Cairo', 'sans-serif'] } } }
        }
    </script>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        @keyframes checkmark { 0% { transform: scale(0) rotate(-45deg); } 100% { transform: scale(1) rotate(0deg); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes confetti { 0% { transform: translateY(0) rotate(0); opacity: 1; } 100% { transform: translateY(-100px) rotate(720deg); opacity: 0; } }
        .check-anim { animation: checkmark 0.5s ease-out 0.2s both; }
        .fade-up { animation: fadeUp 0.5s ease-out 0.4s both; }
        .fade-up-2 { animation: fadeUp 0.5s ease-out 0.6s both; }
        .fade-up-3 { animation: fadeUp 0.5s ease-out 0.8s both; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">

        <!-- Confetti Effect -->
        <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-2 h-2 bg-yellow-400 rounded-full" style="animation: confetti 1.5s ease-out 0.5s both; transform-origin: center;"></div>
                <div class="w-2 h-2 bg-emerald-400 rounded-full ml-4" style="animation: confetti 1.5s ease-out 0.7s both; transform-origin: center;"></div>
                <div class="w-2 h-2 bg-blue-400 rounded-full mr-4" style="animation: confetti 1.5s ease-out 0.9s both; transform-origin: center;"></div>
            </div>

            <!-- Success Check -->
            <div class="mb-8 check-anim">
                <div class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3 fade-up">تم استلام طلبك بنجاح!</h1>
        <p class="text-gray-500 mb-6 fade-up">سنتواصل معك قريباً لتأكيد الطلب</p>

        <!-- Order Number Card -->
        <div class="bg-gradient-to-br from-primary-600 to-purple-700 rounded-3xl p-6 mb-6 text-white fade-up-2">
            <p class="text-primary-200 text-sm mb-1">رقم الطلب</p>
            <p class="text-3xl font-black tracking-wider" dir="ltr">{{ $order->order_number }}</p>
            <div class="mt-4 flex items-center justify-center gap-2">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">الدفع عند الاستلام</span>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-3xl shadow-lg p-6 mb-6 fade-up-2">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">العميل</span>
                    <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">الهاتف</span>
                    <span class="font-bold text-gray-800" dir="ltr">{{ $order->customer_phone }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">العنوان</span>
                    <span class="font-bold text-gray-800">{{ $order->wilaya }} - {{ $order->commune }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">التوصيل</span>
                    <span class="font-bold text-gray-800">{{ number_format($order->shipping_cost, 0, ',', '.') }} د.ج</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-900 font-bold text-base">المجموع الكلي</span>
                    <span class="text-xl font-black text-primary-600">{{ number_format($order->total, 0, ',', '.') }} د.ج</span>
                </div>
            </div>
        </div>

        <!-- Tracking Info -->
        <div class="bg-white rounded-3xl shadow-lg p-6 mb-6 fade-up-3">
            <h3 class="font-bold text-gray-900 mb-4">تتبع طلبك</h3>
            <div x-data="{ tab: 'track', tracking: '', result: null, loading: false }">
                <div class="flex gap-2 mb-4">
                    <input type="text" x-model="tracking" value="{{ $order->order_number }}" class="flex-1 bg-gray-100 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" dir="ltr">
                    <button @click="loading = true; result = null; $fetch('/track/' + tracking).then(r => r.json()).then(d => { result = d; loading = false; }).catch(() => { result = { success: false }; loading = false; })" :disabled="loading" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-colors disabled:opacity-50">
                        <span x-show="!loading">تتبع</span>
                        <span x-show="loading" x-cloak>...</span>
                    </button>
                </div>
                <div x-show="result?.success" x-cloak class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-left" dir="ltr">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">الحالة:</span>
                        <span class="font-bold text-emerald-600" x-text="result?.order?.status_label"></span>
                    </div>
                    <template x-if="result?.order?.tracking_number">
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-gray-600">رقم التتبع:</span>
                            <span class="font-bold text-primary-600" x-text="result?.order?.tracking_number"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 fade-up-3">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                <div class="text-right">
                    <p class="font-bold text-amber-800 text-sm">ملاحظة مهمة</p>
                    <p class="text-amber-700 text-xs mt-1">الدفع عند الاستلام (COD). سيتم التواصل معك خلال 24 ساعة لتأكيد الطلب. يرجى التأكد من صحة رقم الهاتف.</p>
                </div>
            </div>
        </div>

        <a href="{{ route('storefront.show', $slug) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 transition-colors font-semibold text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
            العودة للمتجر
        </a>
    </div>
</body>
</html>
