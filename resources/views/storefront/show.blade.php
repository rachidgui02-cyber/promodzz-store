<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }}</title>
    <meta name="description" content="{{ $shop->description ?? $shop->name . ' - متجر إلكتروني' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { cairo: ['Cairo', 'sans-serif'] } } }
        }
    </script>
    <style>* { font-family: 'Cairo', sans-serif; }</style>
    @if($shop->facebook_pixel_id)
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $shop->facebook_pixel_id }}');
    fbq('track', 'PageView');
    </script>
    @endif
    @if($shop->tiktok_pixel_id)
    <script>
    !function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e+""]=+new Date,ttq._o=ttq._o||{},ttq._o[e+""]=n||{};var a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=r+"?sdkid="+e+"&lib="+t;var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(a,s)};
    ttq.load('{{ $shop->tiktok_pixel_id }}');
    ttq.page();
    </script>
    @endif
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($shop->logo)
                    <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}" class="w-10 h-10 rounded-xl object-cover">
                @endif
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">{{ $shop->name }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="#track" class="text-sm font-bold text-gray-600 hover:text-primary-600 transition-colors hidden sm:block">تتبع طلبك</a>
                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">الدفع عند الاستلام</span>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-primary-600 via-primary-700 to-purple-800 text-white">
        <div class="container mx-auto px-4 py-12 sm:py-20 max-w-6xl">
            <div class="text-center">
                <h2 class="text-3xl sm:text-5xl font-black mb-4 leading-tight">مرحباً بكم في<br><span class="text-yellow-300">{{ $shop->name }}</span></h2>
                <p class="text-primary-100 text-lg sm:text-xl mb-8 max-w-2xl mx-auto">{{ $shop->description ?? 'اكتشف أفضل المنتجات بأسعار مميزة مع التوصيل لجميع الولايات' }}</p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/></svg>
                        <span class="font-bold">توصيل سريع</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                        <span class="font-bold">الدفع عند الاستلام</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        <span class="font-bold">ضمان الجودة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Track Bar -->
    <div class="bg-white border-b border-gray-100" id="track">
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            <div x-data="{ tab: 'products' }" class="max-w-2xl mx-auto">
                <div class="flex gap-2 mb-4">
                    <button @click="tab = 'products'" :class="tab === 'products' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="flex-1 py-2.5 rounded-xl text-sm font-bold transition-colors">المنتجات</button>
                    <button @click="tab = 'track'" :class="tab === 'track' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="flex-1 py-2.5 rounded-xl text-sm font-bold transition-colors">تتبع الطلب</button>
                </div>
                <div x-show="tab === 'products'">
                    <form action="{{ route('storefront.show', $shop->slug) }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن منتج..." class="w-full bg-gray-100 rounded-xl px-5 py-3 pl-12 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                            <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div x-show="tab === 'track'" x-cloak>
                    <div x-data="{ tracking: '', result: null, loading: false }" class="space-y-3">
                        <div class="flex gap-2">
                            <input type="text" x-model="tracking" placeholder="أدخل رقم الطلب (ORD-...)" class="flex-1 bg-gray-100 rounded-xl px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all" dir="ltr">
                            <button @click="loading = true; result = null; $fetch('/track/' + tracking).then(r => r.json()).then(d => { result = d; loading = false; }).catch(() => { result = { success: false }; loading = false; })" :disabled="loading || !tracking" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-colors disabled:opacity-50">
                                <span x-show="!loading">تتبع</span>
                                <span x-show="loading" x-cloak>...</span>
                            </button>
                        </div>
                        <div x-show="result" x-cloak class="p-4 rounded-xl text-sm" :class="result?.success ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200'">
                            <template x-if="result?.success">
                                <div>
                                    <p class="font-bold text-gray-900 mb-2">طلب #<span x-text="result?.order?.order_number"></span></p>
                                    <div class="space-y-1 text-gray-600">
                                        <p>العميل: <span class="font-bold text-gray-900" x-text="result?.order?.customer_name"></span></p>
                                        <p>المبلغ: <span class="font-bold text-primary-600" x-text="result?.order?.total + ' د.ج'"></span></p>
                                        <p>الحالة: <span class="font-bold" :class="result?.order?.status === 'delivered' ? 'text-emerald-600' : 'text-primary-600'" x-text="result?.order?.status_label"></span></p>
                                        @if($shop->default_shipping_cost)
                                        <p>التوصيل: <span class="font-bold">{{ number_format($shop->default_shipping_cost) }} د.ج</span></p>
                                        @endif
                                    </div>
                                </div>
                            </template>
                            <template x-if="!result?.success">
                                <p class="text-red-600 font-bold">لم يتم العثور على الطلب. تأكد من الرقم.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    @if($products->count() > 0)
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold text-gray-900">المنتجات</h3>
            @if(request('category'))
                <a href="{{ route('storefront.show', $shop->slug) }}" class="text-sm font-bold text-primary-600 hover:text-primary-700">عرض الكل ✕</a>
            @endif
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
            <a href="{{ route('storefront.product', [$shop->slug, $product->id]) }}" class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group border border-gray-100 hover:border-primary-200">
                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <svg class="w-16 h-16 text-gray-300 group-hover:text-primary-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                        </svg>
                    @endif
                    @if($product->stock_quantity <= $product->low_stock_threshold)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">متبقي {{ $product->stock_quantity }}</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm leading-tight mb-2 group-hover:text-primary-600 transition-colors">{{ $product->name }}</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-black text-primary-600">{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            <span class="text-xs font-bold text-gray-500">د.ج</span>
                        </div>
                        @if($product->category)
                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">{{ $product->category->name }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="container mx-auto px-4 py-20 text-center">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
        <h3 class="text-xl font-bold text-gray-500 mb-2">لا توجد منتجات حالياً</h3>
        <p class="text-gray-400">سيتم إضافة المنتجات قريباً</p>
    </div>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-bold text-white mb-2">{{ $shop->name }}</p>
            @if($shop->phone)
                <p class="text-sm mb-2">📞 {{ $shop->phone }}</p>
            @endif
            @if($shop->address)
                <p class="text-sm mb-4">📍 {{ $shop->address }}{{ $shop->wilaya ? ', ' . $shop->wilaya : '' }}</p>
            @endif
            <p class="text-xs">&copy; {{ date('Y') }} {{ $shop->name }}. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

</body>
</html>
