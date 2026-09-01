<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $shop->name }}</title>
    <meta name="description" content="{{ $product->description ?? $product->name }} - الدفع عند الاستلام">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { cairo: ['Cairo', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#fdf4ff',100:'#fae8ff',200:'#f5d0fe',300:'#f0abfc',400:'#e879f9',500:'#d946ef',600:'#c026d3',700:'#a21caf',800:'#86198f',900:'#701a75' },
                        primary: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81' },
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        @keyframes count-pulse { 0% { transform:scale(1); } 50% { transform:scale(1.3); } 100% { transform:scale(1); } }
        .count-pulse { animation: count-pulse 0.3s ease; }
        @keyframes field-red-pulse {
            0%, 100% { border-color: #ef4444; background-color: #fef2f2; box-shadow: 0 0 0 2px rgba(239,68,68,0.15); transform: scale(1); }
            50% { border-color: #dc2626; background-color: #fee2e2; box-shadow: 0 0 0 6px rgba(239,68,68,0.45), 0 0 20px rgba(239,68,68,0.2); transform: scale(1.02); }
        }
        @keyframes field-green-pulse {
            0%, 100% { border-color: #22c55e; background-color: #f0fdf4; box-shadow: 0 0 0 2px rgba(34,197,94,0.1); transform: scale(1); }
            50% { border-color: #16a34a; background-color: #dcfce7; box-shadow: 0 0 0 6px rgba(34,197,94,0.3), 0 0 20px rgba(34,197,94,0.15); transform: scale(1.015); }
        }
        .field-active { animation: field-red-pulse 0.9s ease-in-out infinite !important; }
        .field-done { animation: field-green-pulse 1.8s ease-in-out infinite !important; }
        @keyframes cta-glowing {
            0%, 100% { box-shadow: 0 4px 15px rgba(16,185,129,0.3); transform: scale(1) rotate(0deg); }
            10% { transform: scale(1.04) rotate(-0.5deg); }
            20% { transform: scale(1.06) rotate(0.5deg); }
            30% { transform: scale(1.08) rotate(-0.3deg); }
            40% { box-shadow: 0 4px 40px rgba(16,185,129,0.9), 0 0 60px rgba(16,185,129,0.4); transform: scale(1.1) rotate(0deg); }
            50% { box-shadow: 0 4px 50px rgba(16,185,129,1), 0 0 80px rgba(16,185,129,0.5); transform: scale(1.12); }
            60% { box-shadow: 0 4px 40px rgba(16,185,129,0.9), 0 0 60px rgba(16,185,129,0.4); transform: scale(1.1) rotate(0.3deg); }
            70% { transform: scale(1.08) rotate(-0.5deg); }
            80% { transform: scale(1.06) rotate(0.5deg); }
            90% { transform: scale(1.04) rotate(-0.3deg); }
        }
        .cta-glow { animation: cta-glowing 1s ease-in-out infinite !important; }
        .product-description h1 { font-size: 1.5em; font-weight: 700; margin: 0.8em 0 0.4em; color: #1e40af; }
        .product-description h2 { font-size: 1.3em; font-weight: 700; margin: 0.7em 0 0.3em; color: #1e3a8a; }
        .product-description h3 { font-size: 1.1em; font-weight: 700; margin: 0.5em 0 0.2em; color: #1e3a8a; }
        .product-description p { margin: 0.5em 0; line-height: 1.8; }
        .product-description ul, .product-description ol { padding-right: 1.5em; margin: 0.5em 0; }
        .product-description li { margin: 0.3em 0; }
        .product-description img { max-width: 100%; border-radius: 12px; margin: 1em 0; }
        .product-description blockquote { border-right: 4px solid #6366f1; padding: 0.8em 1em; margin: 1em 0; background: #eef2ff; border-radius: 0 8px 8px 0; color: #3730a3; }
        .product-description a { color: #4f46e5; text-decoration: underline; }
        .product-description strong { color: #111827; }
        .product-description table { width: 100%; border-collapse: collapse; margin: 1em 0; }
        .product-description th, .product-description td { border: 1px solid #e5e7eb; padding: 0.5em 0.8em; text-align: right; }
        .product-description th { background: #f3f4f6; font-weight: 700; }
    </style>
    @if($shop->facebook_pixel_id)
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $shop->facebook_pixel_id }}');
    fbq('track', 'PageView');
    fbq('track', 'ViewContent', {
      content_name: '{{ $product->name }}',
      content_ids: ['{{ $product->id }}'],
      content_type: 'product',
      value: {{ $product->sell_price }},
      currency: 'DZD'
    });
    </script>
    @endif
    @if($shop->tiktok_pixel_id)
    <script>
    !function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e+""]=+new Date,ttq._o=ttq._o||{},ttq._o[e+""]=n||{};var a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=r+"?sdkid="+e+"&lib="+t;var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(a,s)};
    ttq.load('{{ $shop->tiktok_pixel_id }}');
    ttq.page();
    ttq.track('ViewContent', {
      content_name: '{{ $product->name }}',
      content_id: '{{ $product->id }}',
      content_type: 'product',
      value: {{ $product->sell_price }},
      currency: 'DZD'
    });
    </script>
    @endif
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Top Trust Bar -->
    <div class="bg-gradient-to-l from-primary-600 to-primary-800 text-white text-center py-2 text-sm font-semibold">
        <div class="container mx-auto px-4 flex items-center justify-center gap-6 flex-wrap">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                ضمان الاسترجاع
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                توصيل لكل الولايات
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                الدفع عند الاستلام
            </span>
        </div>
    </div>

    <!-- Product Section -->
    <div class="container mx-auto px-4 py-6 max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

            <!-- Product Image -->
            <div class="slide-up">
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden sticky top-6">
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-8">
                                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                                </svg>
                                <p class="text-gray-400 font-semibold">{{ $product->name }}</p>
                            </div>
                        @endif
                        <!-- Stock Badge -->
                        @if($product->stock_quantity <= $product->low_stock_threshold)
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            متبقي {{ $product->stock_quantity }} فقط!
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info + Order Form -->
            <div class="slide-up" style="animation-delay: 0.15s">

                <!-- Product Info -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full">جديد</span>
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">✓ متوفر</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3 leading-tight">{{ $product->name }}</h1>

                    <!-- Price -->
                    <div class="mt-4 flex items-baseline gap-3">
                        <span class="text-4xl font-black text-primary-600">{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                        <span class="text-lg font-bold text-gray-500">د.ج</span>
                    </div>
                </div>

                <!-- Order Form -->
                <div id="order-form" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-5 sm:p-7">

                    @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-5">
                        @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-xs flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ $error }}
                        </p>
                        @endforeach
                    </div>
                    @endif

                    <form action="{{ route('storefront.order', $shop->slug) }}" method="POST" id="orderForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Form Header -->
                        <div class="flex items-center gap-2 mb-5">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                            <h3 class="text-base font-extrabold text-gray-900">املأ النموذج أدناه للطلب</h3>
                        </div>

                        <!-- Name + Phone -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div id="nameWrapper">
                                <input type="text" name="customer_name" id="nameInput" value="{{ old('customer_name') }}" required placeholder="الاسم الكامل" class="field-active w-full px-4 py-3 bg-gray-50 border-2 border-red-400 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 transition-all duration-300 focus:border-primary-500 focus:bg-white">
                            </div>
                            <div id="phoneWrapper">
                                <input type="tel" name="customer_phone" id="phoneInput" value="{{ old('customer_phone') }}" required pattern="[0-9]{10}" maxlength="10" placeholder="رقم هاتفك" dir="ltr" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 transition-all duration-300 focus:border-primary-500 focus:bg-white">
                            </div>
                        </div>

                        <!-- Wilaya + Commune -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div id="wilayaWrapper">
                                <select name="wilaya" required id="wilayaSelect" class="w-full px-3 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 text-sm font-medium transition-all duration-300 focus:border-primary-500 appearance-none focus:bg-white" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236b7280%22 stroke-width=%222%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19.5 8.25l-7.5 7.5-7.5-7.5%22/></svg>'); background-repeat: no-repeat; background-position: left 8px center; background-size: 18px;">
                                    <option value="">الولاية</option>
                                @php $wilayas = ['01'=>'Adrar','02'=>'Chlef','03'=>'Laghouat','04'=>'Oum El Bouaghi','05'=>'Batna','06'=>'Béjaïa','07'=>'Biskra','08'=>'Béchar','09'=>'Blida','10'=>'Bouira','11'=>'Tamanrasset','12'=>'Tébessa','13'=>'Tlemcen','14'=>'Tiaret','15'=>'Tizi Ouzou','16'=>'Alger','17'=>'Djelfa','18'=>'Jijel','19'=>'Sétif','20'=>'Saïda','21'=>'Skikda','22'=>'Sidi Bel Abbès','23'=>'Annaba','24'=>'Guelma','25'=>'Constantine','26'=>'Médéa','27'=>'Mostaganem','28'=>'M\'Sila','29'=>'Mascara','30'=>'Ouargla','31'=>'Oran','32'=>'El Bayadh','33'=>'Illizi','34'=>'Bordj Bou Arreridj','35'=>'Boumerdès','36'=>'El Tarf','37'=>'Tindouf','38'=>'Tissemsilt','39'=>'El Oued','40'=>'Khenchela','41'=>'Souk Ahras','42'=>'Tipaza','43'=>'Mila','44'=>'Aïn Defla','45'=>'Naâma','46'=>'Aïn Témouchent','47'=>'Ghardaïa','48'=>'Relizane','49'=>'Timimoun','50'=>'Bordj Badji Mokhtar','51'=>'Ouled Djellal','52'=>'Beni Abbes','53'=>'In Salah','54'=>'In Guezzam','55'=>'Touggourt','56'=>'Djanet','57'=>'El M\'Ghair','58'=>'El Meniaa']; @endphp
                                @foreach($wilayas as $code => $name)
                                <option value="{{ $name }}" {{ old('wilaya') === $name ? 'selected' : '' }}>{{ $code }} - {{ $name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div id="communeWrapper">
                                <select name="commune" required id="communeSelect" class="w-full px-3 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 text-sm font-medium transition-all duration-300 focus:border-primary-500 appearance-none focus:bg-white" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236b7280%22 stroke-width=%222%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19.5 8.25l-7.5 7.5-7.5-7.5%22/></svg>'); background-repeat: no-repeat; background-position: left 8px center; background-size: 18px;">
                                    <option value="">البلدية</option>
                                </select>
                            </div>
                        </div>

                        <!-- Delivery Type -->
                        <div class="mb-4" id="deliveryWrapper">
                            <label class="block text-gray-900 text-sm font-extrabold mb-3">اختر طريقة شحن</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="delivery_type" value="home" {{ old('delivery_type', 'home') === 'home' ? 'checked' : '' }} class="peer hidden" required>
                                    <div class="border-2 border-gray-200 rounded-2xl p-4 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all hover:border-gray-300 flex flex-col items-center gap-1 relative">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center peer-checked:block hidden absolute top-2 left-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        </div>
                                        <svg class="w-6 h-6 text-gray-400 peer-checked:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                        <span class="font-extrabold text-gray-900 text-sm peer-checked:text-emerald-700">للمنزل</span>
                                        <span class="text-xs text-gray-500 font-bold" id="homePrice">{{ number_format($shippingCost, 0, ',', '.') }} د.ج</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="delivery_type" value="stop_desk" {{ old('delivery_type') === 'stop_desk' ? 'checked' : '' }} class="peer hidden">
                                    <div class="border-2 border-gray-200 rounded-2xl p-4 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all hover:border-gray-300 flex flex-col items-center gap-1 relative">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white items-center justify-center hidden absolute top-2 left-2" id="stopDeskCheck">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        </div>
                                        <svg class="w-6 h-6 text-gray-400 peer-checked:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
                                        <span class="font-extrabold text-gray-900 text-sm peer-checked:text-emerald-700">للمكتب</span>
                                        <span class="text-xs text-gray-500 font-bold" id="stopDeskPrice">000 د.ج</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Stop Desk Warning + Dropdown (only visible when stop_desk selected and commune has no office) -->
                        <div id="stopDeskWarning" class="hidden mb-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-start gap-2 mb-2">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                                <p class="text-amber-800 text-xs font-bold" id="stopDeskWarningText"></p>
                            </div>
                            <div id="stopDeskDropdownContainer" class="hidden">
                                <label class="block text-amber-900 text-xs font-extrabold mb-1.5">اختر مكتب التوصيل الأقرب إليك في ولايتك:</label>
                                <select id="stopDeskSelect" class="w-full px-3 py-2.5 bg-white border-2 border-amber-300 rounded-xl text-gray-900 text-sm font-medium transition-all duration-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 appearance-none" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%23d97706%22 stroke-width=%222%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19.5 8.25l-7.5 7.5-7.5-7.5%22/></svg>'); background-repeat: no-repeat; background-position: left 10px center; background-size: 16px;">
                                    <option value="">-- اختر المكتب --</option>
                                </select>
                                <input type="hidden" name="stop_desk_commune" id="stopDeskCommuneInput" value="">
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-2xl p-4 mb-4">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-bold">{{ $product->name }} <span id="qtySummary">×1</span></span>
                                    <span class="text-gray-900 font-extrabold" id="subtotalDisplay">{{ number_format($product->sell_price, 0, ',', '.') }} د.ج</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 font-bold" id="shippingLabel">التوصيل إلى المنزل</span>
                                    <span class="text-gray-600 font-bold" id="shippingDisplay">{{ number_format($shippingCost, 0, ',', '.') }} د.ج</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between items-center">
                                    <span class="text-gray-900 font-extrabold text-base">الإجمالي</span>
                                    <span class="text-xl font-black text-emerald-600" id="totalDisplay">{{ number_format($product->sell_price + $shippingCost, 0, ',', '.') }} د.ج</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity + CTA -->
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex items-center bg-gray-100 rounded-xl overflow-hidden">
                                <button type="button" onclick="changeQty(-1)" class="w-11 h-11 flex items-center justify-center text-gray-600 font-bold text-lg hover:bg-gray-200 transition-colors">-</button>
                                <input type="number" name="quantity" id="qty" value="{{ old('quantity', 1) }}" min="1" max="10" readonly class="w-10 h-11 text-center text-base font-extrabold bg-transparent border-0 text-gray-900">
                                <button type="button" onclick="changeQty(1)" class="w-11 h-11 flex items-center justify-center text-gray-600 font-bold text-lg hover:bg-gray-200 transition-colors">+</button>
                            </div>
                            <button type="submit" id="submitBtn" class="flex-1 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-base rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                اطلب الآن
                            </button>
                        </div>

                        <p class="text-center text-gray-400 text-xs font-bold">سيتم تأكيد طلبك عبر المكالمة 📞</p>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
    <div class="container mx-auto px-4 max-w-5xl mt-8">
        <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h2 class="text-xl font-extrabold text-gray-900">وصف المنتج</h2>
            </div>
            <div class="product-description text-gray-700 leading-relaxed">{!! $product->description !!}</div>
        </div>
    </div>
    @endif

    <!-- Features Section -->
    <div class="bg-white mt-12 py-10 border-t border-gray-100">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-primary-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <p class="font-bold text-gray-800 text-sm">ضمان الجودة</p>
                </div>
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                    <p class="font-bold text-gray-800 text-sm">توصيل سريع</p>
                </div>
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    </div>
                    <p class="font-bold text-gray-800 text-sm">الدفع عند الاستلام</p>
                </div>
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    </div>
                    <p class="font-bold text-gray-800 text-sm">إرجاع سهل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-6 text-center text-sm">
        <p>&copy; {{ date('Y') }} {{ $shop->name }}. جميع الحقوق محفوظة.</p>
    </footer>

    <script>
        const unitPrice = {{ $product->sell_price }};
        const shippingCost = {{ $shippingCost }};

        function changeQty(delta) {
            const input = document.getElementById('qty');
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            if (val > 10) val = 10;
            input.value = val;
            updateTotals();
        }

        function updateTotals() {
            const qty = parseInt(document.getElementById('qty').value);
            const subtotal = unitPrice * qty;
            const total = subtotal + shippingCost;
            document.getElementById('qtySummary').textContent = '×' + qty;
            document.getElementById('subtotalDisplay').textContent = formatDA(subtotal);
            document.getElementById('totalDisplay').textContent = formatDA(total);
        }

        function formatDA(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const communesByWilaya = {
            'Adrar': ['Bouda','Fenoughil','In Zghmir','Ouled Ahmed Timmi','Reggane','Sali','Sebaa','Tamantit','Tamest','Timekten','Tit','Tsabit','Zaouiet Kounta'],
            'Chlef': ['Abou El Hassan','Ain Merane','Benairia','Beni Bouattab','Beni Haoua','Beni Rached','Boukadir','Bouzeghaia','Breira','Chettia','Chlef','Dahra','El Hadjadj','El Karimia','El Marsa','Harchoun','Herenfa','Labiod Medjadja','Moussadek','Oued Fodda','Oued Goussine','Oued Sly','Ouled Abbes','Ouled Ben Abdelkader','Ouled Fares','Oum Drou','Sendjas','Sidi Abderrahmane','Sidi Akkacha','Sobha','Tadjena','Talassa','Taougrite','Tenes','Zeboudja'],
            'Laghouat': ['Aflou','Ain Madhi','Ain Sidi Ali','Beidha','Benacer Benchohra','Brida','El Assafia','El Ghicha','El Haouaita','Gueltat Sidi Saad','Hadj Mechri','Hassi Delaa','Hassi R\'mel','Kheneg','Ksar El Hirane','Laghouat','Oued M\'zi','Oued Morra','Sebgag','Sidi Bouzid','Sidi Makhlouf','Tadjemout','Tadjrouna','Taouiala'],
            'Oum El Bouaghi': ['Ain Babouche','Ain Beida','Ain Diss','Ain Fekroune','Ain Kercha','Ain M\'lila','Ain Zitoun','Behir Chergui','Berriche','Bir Chouhada','Dhala','El Amiria','El Belala','El Djazia','El Fedjoudj Boughrara Sa','El Harmilia','Fkirina','Hanchir Toumghani','Ksar Sbahi','Meskiana','Oued Nini','Ouled Gacem','Ouled Hamla','Ouled Zouai','Oum El Bouaghi','Rahia','Sigus','Souk Naamane','Zorg'],
            'Batna': ['Ain Djasser','Ain Touta','Ain Yagout','Arris','Azil Abedelkader','Barika','Batna','Beni Foudhala El Hakania','Bitam','Boulhilat','Boumagueur','Boumia','Bouzina','Chemora','Chir','Djerma','Djezzar','El Hassi','El Madher','Fesdis','Foum Toub','Ghassira','Gosbat','Guigba','Hidoussa','Ichmoul','Inoughissen','Kimmel','Ksar Bellezma','Larbaa','Lazrou','Lemsane','M Doukal','Maafa','Menaa','Merouana','N Gaous','Oued Chaaba','Oued El Ma','Oued Taga','Ouled Ammar','Ouled Aouf','Ouled Fadel','Ouled Sellem','Ouled Si Slimane','Ouyoun El Assafir','Rahbat','Ras El Aioun','Sefiane','Seggana','Seriana','T Kout','Talkhamt','Taxlent','Tazoult','Teniet El Abed','Tighanimine','Tigharghar','Tilatou','Timgad','Zanet El Beida'],
            'Béjaïa': ['Adekar','Ait R\'zine','Ait Smail','Akbou','Akfadou','Amalou','Amizour','Aokas','Barbacha','Bejaia','Beni Dejllil','Beni K\'sila','Beni Mallikeche','Benimaouche','Boudjellil','Bouhamza','Boukhelifa','Chellata','Chemini','Darghina','Dra El Caid','El Kseur','Fenaia Il Maten','Feraoun','Ighil Ali','Ighram','Kendira','Kherrata','Leflaye','M\'cisna','Melbou','Oued Ghir','Ouzellaguene','Seddouk','Sidi Aich','Sidi Ayad','Smaoun','Souk El Tenine','Souk Oufella','Tala Hamza','Tamokra','Tamridjet','Taourit Ighil','Taskriout','Tazmalt','Tibane','Tichy','Tifra','Timezrit','Tinebdar','Tizi N\'berber','Toudja'],
            'Biskra': ['Ain Naga','Ain Zaatout','Biskra','Bordj Ben Azzouz','Bouchagroun','Branis','Chetma','Djemorah','El Feidh','El Ghrous','El Hadjab','El Haouch','El Kantara','El Outaya','Foughala','Khenguet Sidi Nadji','Lichana','Lioua','M\'chouneche','M\'lili','Mekhadma','Meziraa','Oumache','Ourlal','Sidi Okba','Tolga','Zeribet El Oued'],
            'Béchar': ['Abadla','Bechar','Beni Ounif','Boukais','Erg Ferradj','Kenadsa','Lahmar','Mechraa H.boumediene','Meridja','Mogheul','Taghit'],
            'Blida': ['Ain Romana','Beni Mered','Beni Tamou','Benkhelil','Blida','Bouarfa','Boufarik','Bougara','Bouinan','Chebli','Chiffa','Chrea','Djebabra','El Affroun','Guerrouaou','Hammam Melouane','Larbaa','Meftah','Mouzaia','Oued Djer','Oued El Alleug','Ouled Slama','Ouled Yaich','Souhane','Souma'],
            'Bouira': ['Aghbalou','Ahl El Ksar','Ain Bessem','Ain El Hadjar','Ain Laloui','Ain Turk','Ait Laaziz','Aomar','Bechloul','Bir Ghbalou','Bordj Okhriss','Bouderbala','Bouira','Boukram','Chorfa','Dechmia','Dirah','Djebahia','El Adjiba','El Asnam','El Hachimia','El Hakimia','El Khabouzia','El Mokrani','Guerrouma','Hadjera Zerga','Haizer','Hanif','Kadiria','Lakhdaria','M Chedallah','Maala','Mamora','Mezdour','Oued El Berdi','Ouled Rached','Raouraoua','Ridane','Saharidj','Souk El Khemis','Sour El Ghozlane','Taghzout','Taguedite','Taourirt','Z\'barbar'],
            'Tamanrasset': ['Abalessa','Ain Amguel','Idles','Tamanrasset','Tazrouk'],
            'Tébessa': ['Ain Zerga','Bedjene','Bekkaria','Bir Dheheb','Bir El Ater','Bir Mokkadem','Boukhadra','Boulhaf Dyr','Cheria','El Aouinet','El Houidjbet','El Kouif','El Malabiod','El Meridj','El Mezeraa','El Ogla','El Ogla El Malha','Ferkane','Guorriguer','Hammamet','Morssot','Negrine','Ouenza','Oum Ali','Saf Saf El Ouesra','Stah Guentis','Tebessa','Telidjen'],
            'Tlemcen': ['Ain Fettah','Ain Fezza','Ain Ghoraba','Ain Kebira','Ain Nehala','Ain Tallout','Ain Youcef','Amieur','Azails','Bab El Assa','Beni Bahdel','Beni Boussaid','Beni Khaled','Beni Mester','Beni Ouarsous','Beni Smiel','Beni Snous','Bensekrane','Bouhlou','Bouihi','Chetouane','Dar Yaghmouracene','Djebala','El Aricha','El Fehoul','El Gor','Fellaoucene','Ghazaouet','Hammam Boughrara','Hennaya','Honaine','Maghnia','Mansourah','Marsa Ben M\'hidi','Msirda Fouaga','Nedroma','Oued Chouly','Ouled Mimoun','Ouled Riyah','Remchi','Sabra','Sebbaa Chioukh','Sebdou','Sidi Abdelli','Sidi Djilali','Sidi Medjahed','Souahlia','Souani','Souk Tleta','Terny Beni Hediel','Tianet','Tlemcen','Zenata'],
            'Tiaret': ['Ain Bouchekif','Ain Deheb','Ain El Hadid','Ain Kermes','Ain Zarit','Bougara','Chehaima','Dahmouni','Djebilet Rosfa','Djillali Ben Amar','Faidja','Frenda','Guertoufa','Hamadia','Ksar Chellala','Madna','Mahdia','Mechraa Safa','Medrissa','Medroussa','Meghila','Mellakou','Nadorah','Naima','Oued Lilli','Rahouia','Rechaiga','Sebaine','Sebt','Serghine','Si Abdelghani','Sidi Abderrahmane','Sidi Ali Mellal','Sidi Bakhti','Sidi Hosni','Sougueur','Tagdemt','Takhemaret','Tiaret','Tidda','Tousnina','Zmalet El Emir Abdelkade'],
            'Tizi Ouzou': ['Abi Youcef','Aghribs','Agouni Gueghrane','Ain El Hammam','Ain Zaouia','Ait Aggouacha','Ait Bouaddou','Ait Boumehdi','Ait Chafaa','Ait Khellili','Ait Mahmoud','Ait Oumalou','Ait Toudert','Ait Yahia','Ait Yahia Moussa','Akbil','Akerrou','Assi Youcef','Azazga','Azeffoun','Beni Aissi','Beni Douala','Beni Yenni','Beni Zikki','Beni Zmenzer','Boghni','Boudjima','Bounouh','Bouzeguene','Djebel Aissa Mimoun','Draa Ben Khedda','Draa El Mizan','Freha','Frikat','Iboudrarene','Idjeur','Iferhounene','Ifigha','Iflissen','Illilten','Illoula Oumalou','Imsouhal','Irdjen','Larba Nath Irathen','Larbaa Nath Irathen','M\'kira','Maatkas','Makouda','Mechtras','Mekla','Mizrana','Ouacif','Ouadhias','Ouaguenoune','Sidi Naamane','Souamaa','Souk El Thenine','Tadmait','Tigzirt','Timizart','Tirmitine','Tizi Ghenif','Tizi N\'tleta','Tizi Ouzou','Tizi Rached','Yakourene','Yatafene','Zekri'],
            'Alger': ['Ain Benian','Ain Taya','Alger Centre','Bab El Oued','Bab Ezzouar','Baba Hesen','Bachedjerah','Bains Romains','Baraki','Ben Aknoun','Beni Messous','Bir Mourad Rais','Bir Touta','Birkhadem','Bologhine Ibnou Ziri','Bordj El Bahri','Bordj El Kiffan','Bourouba','Bouzareah','Casbah','Cheraga','Dar El Beida','Dely Ibrahim','Djasr Kasentina','Douira','Draria','El Achour','El Biar','El Harrach','El Madania','El Magharia','El Merssa','El Mouradia','Herraoua','Hussein Dey','Hydra','Kheraisia','Kouba','Les Eucalyptus','Maalma','Mohamed Belouzdad','Mohammadia','Oued Koriche','Oued Smar','Ouled Chebel','Ouled Fayet','Rahmania','Rais Hamidou','Reghaia','Rouiba','Sehaoula','Setaouali','Sidi M\'hamed','Sidi Moussa','Souidania','Tessala El Merdja','Zeralda'],
            'Djelfa': ['Ain Chouhada','Ain El Ibel','Ain Fekka','Ain Maabed','Ain Oussera','Amourah','Benhar','Benyagoub','Birine','Bouira Lahdab','Charef','Dar Chioukh','Deldoul','Djelfa','Douis','El Guedid','El Idrissia','El Khemis','Faidh El Botma','Guernini','Guettara','Had Sahary','Hassi Bahbah','Hassi El Euch','Hassi Fedoul','M Liliha','Messaad','Moudjebara','Oum Laadham','Sed Rahal','Selmana','Sidi Baizid','Sidi Ladjel','Tadmit','Zaafrane','Zaccar'],
            'Jijel': ['Bordj Tahar','Boudria Beniyadjis','Bouraoui Belhadef','Boussif Ouled Askeur','Chahna','Chekfa','Djemaa Beni Habibi','Djimla','El Ancer','El Aouana','El Kennar Nouchfi','El Milia','Emir Abdelkader','Erraguene','Ghebala','Jijel','Khiri Oued Adjoul','Kouas','Oudjana','Ouled Rabah','Ouled Yahia Khadrouch','Selma Benziada','Settara','Sidi Abdelaziz','Sidi Marouf','Taher','Texena','Ziama Mansouria'],
            'Sétif': ['Ain Abessa','Ain Arnat','Ain Azel','Ain El Kebira','Ain Lahdjar','Ain Legradj','Ain Oulmane','Ain Roua','Ain Sebt','Ait Naoual Mezada','Ait Tizi','Amoucha','Babor','Bazer Sakra','Beidha Bordj','Bellaa','Beni Aziz','Beni Chebana','Beni Fouda','Beni Mouhli','Beni Ouartilane','Beni Oussine','Bir El Arch','Bir Haddada','Bouandas','Bougaa','Bousselam','Boutaleb','Dehamcha','Djemila','Draa Kebila','El Eulma','El Ouldja','El Ouricia','Guellal','Guelta Zerka','Guenzet','Guidjel','Hamam Soukhna','Hamma','Hammam Guergour','Harbil','Ksar El Abtal','Maaouia','Maouaklane','Mezloug','Oued El Barad','Ouled Addouane','Ouled Sabor','Ouled Si Ahmed','Ouled Tebben','Rosfa','Salah Bey','Serdj El Ghoul','Setif','Tachouda','Tala Ifacene','Taya','Tella','Tizi N\'bechar'],
            'Saïda': ['Ain El Hadjar','Ain Sekhouna','Ain Soltane','Doui Thabet','El Hassasna','Hounet','Maamora','Moulay Larbi','Ouled Brahim','Ouled Khaled','Saida','Sidi Ahmed','Sidi Amar','Sidi Boubekeur','Tircine','Youb'],
            'Skikda': ['Ain Bouziane','Ain Charchar','Ain Kechera','Ain Zouit','Azzaba','Bekkouche Lakhdar','Ben Azzouz','Beni Bechir','Beni Oulbane','Beni Zid','Bin El Ouiden','Bouchetata','Cheraia','Collo','Djendel Saadi Mohamed','El Arrouch','El Ghedir','El Hadaiek','El Marsa','Emjez Edchich','Es Sebt','Filfila','Hamadi Krouma','Kanoua','Kerkera','Khenag Mayoum','Oued Zhour','Ouldja Boulbalout','Ouled Attia','Ouled Habbeba','Oum Toub','Ramdane Djamel','Salah Bouchaour','Sidi Mezghiche','Skikda','Tamalous','Zerdezas','Zitouna'],
            'Sidi Bel Abbès': ['Ain Adden','Ain El Berd','Ain Kada','Ain Thrid','Ain Tindamine','Amarnas','Badredine El Mokrani','Belarbi','Ben Badis','Benachiba Chelia','Bir El Hammam','Boudjebaa El Bordj','Boukhanafis','Chetouane Belaila','Dhaya','El Hacaiba','Hassi Dahou','Hassi Zahana','Lamtar','M\'cid','Makedra','Marhoum','Merine','Mezaourou','Mostefa Ben Brahim','Moulay Slissen','Oued Sebaa','Oued Sefioun','Oued Taourira','Ras El Ma','Redjem Demouche','Sehala Thaoura','Sfissef','Sidi Ali Benyoub','Sidi Ali Boussidi','Sidi Bel Abbes','Sidi Brahim','Sidi Chaib','Sidi Dahou Zairs','Sidi Hamadouche','Sidi Khaled','Sidi Lahcene','Sidi Yacoub','Tabia','Tafissour','Taoudmout','Teghalimet','Telagh','Tenira','Tessala','Tilmouni','Zerouala'],
            'Annaba': ['Ain Berda','Annaba','Berrahel','Chetaibi','Cheurfa','El Bouni','El Hadjar','Eulma','Oued El Aneb','Seraidi','Sidi Amar','Treat'],
            'Guelma': ['Ain Ben Beida','Ain Hessania','Ain Larbi','Ain Makhlouf','Ain Reggada','Belkheir','Ben Djarah','Beni Mezline','Bordj Sabat','Bou Hachana','Bou Hamdane','Bouati Mahmoud','Bouchegouf','Bouhamra Ahmed','Dahouara','Djeballah Khemissi','El Fedjoudj','Guelaat Bou Sbaa','Guelma','Hamam Debagh','Hammam N\'bail','Heliopolis','Khezara','Medjez Amar','Medjez Sfa','Nechmaya','Oued Cheham','Oued Fragha','Oued Zenati','Ras El Agba','Roknia','Sellaoua Announa','Sidi Sandel','Tamlouka'],
            'Constantine': ['Ain Abid','Ain Smara','Ben Badis','Beni Hamidene','Constantine','Didouche Mourad','El Khroub','Hamma Bouziane','Ibn Ziad','Messaoud Boujeriou','Ouled Rahmouni','Zighoud Youcef'],
            'Médéa': ['Ain Boucif','Ain Ouksir','Aissaouia','Aziz','Baata','Ben Chicao','Beni Slimane','Berrouaghia','Bir Ben Laabed','Boghar','Bouaiche','Bouaichoune','Bouchrahil','Boughzoul','Bouskene','Chabounia','Chelalet El Adhaoura','Cheniguel','Damiat','Derrag','Deux Bassins','Djouab','Draa Essamar','El Azizia','El Guelbelkebir','El Hamdania','El Omaria','El Ouinet','Hannacha','Kef Lakhdar','Khams Djouamaa','Ksar El Boukhari','Maghraoua','Medea','Medjebar','Meftaha','Mezerana','Mihoub','Ouamri','Oued Harbil','Ouled Antar','Ouled Bouachra','Ouled Brahim','Ouled Deid','Ouled Hellal','Ouled Maaref','Oum El Djellil','Ouzera','Rebaia','Saneg','Sedraya','Seghouane','Si Mahdjoub','Sidi Demed','Sidi Naamane','Sidi Rabie','Sidi Zahar','Sidi Ziane','Souagui','Tablat','Tafraout','Tamesguida','Tletat Ed Douair','Zoubiria'],
            'Mostaganem': ['Achaacha','Ain Boudinar','Ain Nouissy','Ain Sidi Cherif','Ain Tedles','Benabdelmalek Ramdane','Bouguirat','Fornaka','Hadjadj','Hassi Mameche','Hassiane','Khadra','Kheir Eddine','Mansourah','Mazagran','Mesra','Mostaganem','Nekmaria','Oued El Kheir','Ouled Boughalem','Ouled Maalah','Safsaf','Sayada','Sidi Ali','Sidi Belaattar','Sidi Lakhdar','Sirat','Souaflia','Sour','Stidia','Tazgait','Touahria'],
            'M\'Sila': ['Ain El Hadjel','Ain El Melh','Ain Fares','Ain Khadra','Ain Rich','Belaiba','Ben Srour','Beni Ilmane','Benzouh','Berhoum','Bir Foda','Bou Saada','Bouti Sayeh','Chellal','Dehahna','Djebel Messaad','El Hamel','El Houamed','Hammam Dalaa','Khettouti Sed El Jir','Khoubana','M\'cif','M\'sila','M\'tarfa','Maadid','Maarif','Magra','Medjedel','Menaa','Mohamed Boudiaf','Ouanougha','Ouled Addi Guebala','Ouled Derradj','Ouled Madhi','Ouled Mansour','Ouled Sidi Brahim','Ouled Slimane','Oulteme','Sidi Aissa','Sidi Ameur','Sidi Hadjeres','Sidi M\'hamed','Slim','Souamaa','Tamsa','Tarmount','Zarzour'],
            'Mascara': ['Ain Fares','Ain Fekan','Ain Ferah','Ain Frass','Alaimia','Aouf','Benian','Bou Henni','Bouhanifia','Chorfa','El Bordj','El Gaada','El Ghomri','El Gueitena','El Hachem','El Keurt','El Mamounia','El Menaouer','Ferraguig','Froha','Gharrous','Ghriss','Guerdjoum','Hacine','Khalouia','Makhda','Maoussa','Mascara','Matemore','Mocta Douz','Mohammadia','Nesmot','Oggaz','Oued El Abtal','Oued Taria','Ras El Ain Amirouche','Sedjerara','Sehailia','Sidi Abdeldjebar','Sidi Abdelmoumene','Sidi Boussaid','Sidi Kada','Sig','Tighennif','Tizi','Zahana','Zelamta'],
            'Ouargla': ['Ain Beida','El Borma','Hassi Ben Abdellah','Hassi Messaoud','N\'goussa','Ouargla','Rouissat','Sidi Khouiled'],
            'Oran': ['Ain Biya','Ain Kerma','Ain Turk','Arzew','Ben Freha','Bethioua','Bir El Djir','Boufatis','Bousfer','Boutlelis','El Ancar','El Braya','El Kerma','Es Senia','Gdyel','Hassi Ben Okba','Hassi Bounif','Hassi Mefsoukh','Marsat El Hadjadj','Mers El Kebir','Messerghin','Oran','Oued Tlelat','Sidi Ben Yebka','Sidi Chami','Tafraoui'],
            'El Bayadh': ['Ain El Orak','Arbaouat','Boualem','Bougtoub','Boussemghoun','Brezina','Cheguig','Chellala','El Bayadh','El Biodh Sidi Cheikh','El Bnoud','El Kheither','El Mehara','Ghassoul','Kef El Ahmar','Krakda','Rogassa','Sidi Ameur','Sidi Slimane','Sidi Tifour','Stitten','Tousmouline'],
            'Illizi': ['Bordj Omar Driss','Debdeb','Illizi','In Amenas'],
            'Bordj Bou Arreridj': ['Ain Taghrout','Ain Tesra','Belimour','Ben Daoud','Bir Kasdali','Bordj Bou Arreridj','Bordj Ghdir','Bordj Zemora','Colla','Djaafra','El Ach','El Achir','El Anseur','El Hamadia','El M\'hir','El Main','Ghilassa','Haraza','Hasnaoua','Khelil','Ksour','Mansoura','Medjana','Ouled Brahem','Ouled Dahmane','Ouled Sidi Brahim','Rabta','Ras El Oued','Sidi Embarek','Tafreg','Taglait','Teniet En Nasr','Tesmart','Tixter'],
            'Boumerdès': ['Afir','Ammal','Baghlia','Ben Choud','Beni Amrane','Bordj Menaiel','Boudouaou','Boudouaou El Bahri','Boumerdes','Bouzegza Keddara','Chabet El Ameur','Corso','Dellys','Djinet','El Kharrouba','Hammedi','Isser','Khemis El Khechna','Larbatache','Leghata','Naciria','Ouled Aissa','Ouled Hedadj','Ouled Moussa','Si Mustapha','Sidi Daoud','Souk El Haad','Taourga','Thenia','Tidjelabine','Timezrit','Zemmouri'],
            'El Tarf': ['Ain El Assel','Ain Kerma','Asfour','Ben M Hidi','Berrihane','Besbes','Bougous','Bouhadjar','Bouteldja','Chebaita Mokhtar','Chefia','Chihani','Drean','Echatt','El Aioun','El Kala','El Tarf','Hammam Beni Salah','Lac Des Oiseaux','Oued Zitoun','Raml Souk','Souarekh','Zerizer','Zitouna'],
            'Tindouf': ['Oum El Assel','Tindouf'],
            'Tissemsilt': ['Ammari','Beni Chaib','Beni Lahcene','Bordj Bounaama','Bordj El Emir Abdelkader','Bou Caid','Khemisti','Larbaa','Lardjem','Layoune','Lazharia','Maacem','Melaab','Ouled Bessem','Sidi Abed','Sidi Boutouchent','Sidi Lantri','Sidi Slimane','Tamellalet','Theniet El Had','Tissemsilt','Youssoufia'],
            'El Oued': ['Bayadha','Ben Guecha','Debila','Douar El Maa','El Ogla','El Oued','Guemar','Hamraia','Hassani Abdelkrim','Hassi Khalifa','Kouinine','Magrane','Mih Ouansa','Nakhla','Oued El Alenda','Ourmes','Reguiba','Robbah','Sidi Aoun','Taghzout','Taleb Larbi','Trifaoui'],
            'Khenchela': ['Ain Touila','Babar','Baghai','Bouhmama','Chelia','Cherchar','Djellal','El Hamma','El Mahmal','El Oueldja','Ensigha','Kais','Khenchela','Khirane','M\'sara','M\'toussa','Ouled Rechache','Remila','Tamza','Taouzianat','Yabous'],
            'Souk Ahras': ['Ain Soltane','Ain Zana','Bir Bouhouche','Drea','Haddada','Hanencha','Khedara','Khemissa','M\'daourouche','Machroha','Merahna','Oued Kebrit','Ouled Driss','Ouled Moumen','Oum El Adhaim','Quillen','Ragouba','Safel El Ouiden','Sedrata','Sidi Fredj','Souk Ahras','Taoura','Terraguelt','Tiffech','Zaarouria','Zouabi'],
            'Tipaza': ['Aghbal','Ahmer El Ain','Ain Tagourait','Attatba','Beni Mileuk','Bou Haroun','Bou Ismail','Bourkika','Chaiba','Cherchell','Damous','Douaouda','Fouka','Gouraya','Hadjout','Hadjret Ennous','Khemisti','Kolea','Larhat','Menaceur','Merad','Messelmoun','Nador','Sidi Amar','Sidi Ghiles','Sidi Rached','Sidi Semiane','Tipaza'],
            'Mila': ['Ahmed Rachedi','Ain Beida Harriche','Ain Mellouk','Ain Tine','Amira Arres','Benyahia Abderrahmane','Bouhatem','Chelghoum Laid','Chigara','Derrahi Bousselah','El Mechira','Elayadi Barbes','Ferdjioua','Grarem Gouga','Hamala','Mila','Minar Zarza','Oued Athmenia','Oued Endja','Oued Seguen','Ouled Khalouf','Rouached','Sidi Khelifa','Sidi Merouane','Tadjenanet','Tassadane Haddada','Teleghma','Terrai Bainem','Tessala','Tiberguent','Yahia Beniguecha','Zeghaia'],
            'Aïn Defla': ['Ain Benian','Ain Bouyahia','Ain Defla','Ain Lechiakh','Ain Soltane','Ain Tork','Arib','Barbouche','Bathia','Belaas','Ben Allal','Bir Ould Khelifa','Bordj Emir Khaled','Boumedfaa','Bourached','Djelida','Djemaa Ouled Cheikh','Djendel','El Abadia','El Amra','El Attaf','El Maine','Hammam Righa','Hassania','Hoceinia','Khemis Miliana','Mekhatria','Miliana','Oued Chorfa','Oued Djemaa','Rouina','Sidi Lakhdar','Tacheta Zegagha','Tarik Ibn Ziad','Tiberkanine','Zeddine'],
            'Naâma': ['Ain Ben Khelil','Ain Safra','Assela','Djeniane Bourzeg','El Biod','Kasdir','Makman Ben Amer','Mecheria','Moghrar','Naama','Sfissifa','Tiout'],
            'Aïn Témouchent': ['Aghlal','Ain El Arbaa','Ain Kihal','Ain Temouchent','Ain Tolba','Aoubellil','Beni Saf','Bouzedjar','Chaabat El Ham','Chentouf','El Amria','El Malah','El Messaid','Emir Abdelkader','Hammam Bouhadjar','Hassasna','Hassi El Ghella','Oued Berkeche','Oued Sebbah','Ouled Boudjemaa','Ouled Kihal','Oulhaca El Gheraba','Sidi Ben Adda','Sidi Boumediene','Sidi Ouriache','Sidi Safi','Tamzoura','Terga'],
            'Ghardaïa': ['Berriane','Bounoura','Dhayet Bendhahoua','El Atteuf','El Guerrara','Ghardaia','Mansoura','Metlili','Sebseb','Zelfana'],
            'Relizane': ['Ain Rahma','Ain Tarek','Ammi Moussa','Belaassel Bouzagza','Bendaoud','Beni Dergoun','Beni Zentis','Dar Ben Abdelah','Djidiouia','El Guettar','El H\'madna','El Hassi','El Matmar','El Ouldja','Had Echkalla','Hamri','Kalaa','Lahlef','Mazouna','Mediouna','Mendes','Merdja Sidi Abed','Ouarizane','Oued El Djemaa','Oued Essalem','Oued Rhiou','Ouled Aiche','Ouled Sidi Mihoub','Ramka','Relizane','Sidi Khettab','Sidi Lazreg','Sidi M\'hamed Benali','Sidi M\'hamed Benaouda','Sidi Saada','Souk El Had','Yellel','Zemmoura'],
            'Timimoun': ['Aougrout','Charouine','Deldoul','Ksar Kaddour','Metarfa','Ouled Aissa','Ouled Said','Talmine','Timimoun','Tinerkouk'],
            'Bordj Badji Mokhtar': ['Bordj Badji Mokhtar','Timiaouine'],
            'Ouled Djellal': ['Besbes','Chaiba','Doucen','Ouled Djellal','Ras El Miad','Sidi Khaled'],
            'Beni Abbes': ['Beni Abbes','Beni Ikhlef','El Ouata','Igli','Kerzaz','Ksabi','Ouled Khoudir','Tabelbala','Tamtert','Timoudi'],
            'In Salah': ['Foggaret Azzaouia','In Ghar','In Salah'],
            'In Guezzam': ['In Guezzam','Tin Zouatine'],
            'Touggourt': ['Benaceur','Blidet Amor','El Alia','El Hadjira','Megarine','Mnaguer','Nezla','Sidi Slimane','Taibet','Tebesbest','Temacine','Touggourt','Zaouia El Abidia'],
            'Djanet': ['Bordj El Haouasse','Djanet'],
            'El M\'Ghair': ['Djamaa','El M\'ghair','Mrara','Oum Touyour','Sidi Amrane','Sidi Khelil','Still','Tenedla'],
            'El Meniaa': ['El Meniaa','Hassi Fehal','Hassi Gara']
        };

        const stopDeskByWilaya = {
            'Adrar': ['Adrar'],
            'Chlef': ['Chlef','Tenes'],
            'Laghouat': ['Laghouat'],
            'Oum El Bouaghi': ['Oum El Bouaghi','Ain Fekroune'],
            'Batna': ['Batna','Barika'],
            'Béjaïa': ['Bejaia','Kherrata'],
            'Biskra': ['Biskra'],
            'Béchar': ['Bechar'],
            'Blida': ['Blida','Ouled Yaich','Beni Mered','Bouarfa','Bouinan'],
            'Bouira': ['Bouira'],
            'Tamanrasset': ['Tamanrasset'],
            'Tébessa': ['Tebessa'],
            'Tlemcen': ['Tlemcen','Sebdou'],
            'Tiaret': ['Tiaret','Ksar Chellala'],
            'Tizi Ouzou': ['Tizi Ouzou'],
            'Alger': ['Bab El Oued','Bab Ezzouar','Bir Touta','Les Eucalyptus','Cheraga','Djasr Kasentina','Kouba','Draria','Reghaia'],
            'Djelfa': ['Djelfa','Ain Oussera'],
            'Jijel': ['Jijel'],
            'Sétif': ['Setif','El Eulma'],
            'Saïda': ['Saida'],
            'Skikda': ['Skikda','El Arrouch'],
            'Sidi Bel Abbès': ['Sidi Bel Abbes'],
            'Annaba': ['Annaba','El Bouni'],
            'Guelma': ['Guelma'],
            'Constantine': ['Constantine','El Khroub'],
            'Médéa': ['Medea'],
            'Mostaganem': ['Mostaganem','Mazagran'],
            'M\'Sila': ['M\'sila','Bou Saada'],
            'Mascara': ['Mascara','Sig'],
            'Ouargla': ['Ouargla'],
            'Oran': ['Oran','Bir El Djir','Sidi Chami'],
            'El Bayadh': ['El Bayadh'],
            'Illizi': ['Illizi'],
            'Bordj Bou Arreridj': ['Bordj Bou Arreridj'],
            'Boumerdès': ['Boumerdes','Bordj Menaiel'],
            'El Tarf': ['El Tarf'],
            'Tindouf': ['Tindouf'],
            'Tissemsilt': ['Tissemsilt'],
            'El Oued': ['El Oued'],
            'Khenchela': ['Khenchela'],
            'Souk Ahras': ['Souk Ahras'],
            'Tipaza': ['Tipaza','Kolea'],
            'Mila': ['Mila'],
            'Aïn Defla': ['Ain Defla','Khemis Miliana'],
            'Naâma': ['Mecheria'],
            'Aïn Témouchent': ['Ain Temouchent'],
            'Ghardaïa': ['Ghardaia'],
            'Relizane': ['Relizane'],
            'Timimoun': ['Timimoun'],
            'Ouled Djellal': ['Ouled Djellal'],
            'In Salah': ['In Salah'],
            'Touggourt': ['Touggourt'],
            'El Meniaa': ['El Meniaa']
        };

        const nameInput = document.getElementById('nameInput');
        const phoneInput = document.getElementById('phoneInput');
        const wilayaSelect = document.getElementById('wilayaSelect');
        const communeSelect = document.getElementById('communeSelect');
        const stopDeskRadio = document.querySelector('input[name="delivery_type"][value="stop_desk"]');
        const homeRadio = document.querySelector('input[name="delivery_type"][value="home"]');
        const stopDeskLabel = stopDeskRadio.closest('label');
        const stopDeskWarning = document.getElementById('stopDeskWarning');
        const stopDeskDropdownContainer = document.getElementById('stopDeskDropdownContainer');
        const stopDeskSelect = document.getElementById('stopDeskSelect');
        const stopDeskCommuneInput = document.getElementById('stopDeskCommuneInput');
        const stopDeskWarningText = document.getElementById('stopDeskWarningText');
        const submitBtn = document.getElementById('submitBtn');

        const nameWrapper = document.getElementById('nameWrapper');
        const phoneWrapper = document.getElementById('phoneWrapper');
        const wilayaWrapper = document.getElementById('wilayaWrapper');
        const communeWrapper = document.getElementById('communeWrapper');
        const deliveryWrapper = document.getElementById('deliveryWrapper');

        let communeHasStopDesk = false;
        let deliveryDone = false;
        let communeDone = false;
        let currentStep = 1;

        function activate(el) { var inp = el.querySelector('input, select'); inp.classList.add('field-active'); inp.classList.remove('field-done'); }
        function done(el) { var inp = el.querySelector('input, select'); inp.classList.remove('field-active'); inp.classList.add('field-done'); }
        function neutral(el) { var inp = el.querySelector('input, select'); inp.classList.remove('field-active', 'field-done'); }

        function goToStep(step) {
            neutral(nameWrapper);
            neutral(phoneWrapper);
            neutral(wilayaWrapper);
            neutral(communeWrapper);
            neutral(deliveryWrapper);

            if (step > 1) done(nameWrapper);
            if (step > 2) done(phoneWrapper);
            if (step > 3) done(wilayaWrapper);
            if (step > 4) done(communeWrapper);
            if (step > 5) done(deliveryWrapper);

            if (step === 1) activate(nameWrapper);
            else if (step === 2) activate(phoneWrapper);
            else if (step === 3) activate(wilayaWrapper);
            else if (step === 4) activate(communeWrapper);
            else if (step === 5) activate(deliveryWrapper);

            currentStep = step;
        }

        function checkAllDone() {
            var nameOk = nameInput.value.trim().length >= 1;
            var phoneOk = phoneInput.value.length === 10 && /^0[567]/.test(phoneInput.value);
            var wilayaOk = wilayaSelect.value !== '';
            var communeOk = communeSelect.value !== '';
            if (nameOk && phoneOk && wilayaOk && communeOk && deliveryDone) {
                submitBtn.classList.add('cta-glow');
            } else {
                submitBtn.classList.remove('cta-glow');
            }
        }

        // Step 1: Name — only name is active on load
        activate(nameWrapper);

        // Init deliveryDone if radio already checked
        document.querySelectorAll('input[name="delivery_type"]:checked').forEach(function(r) {
            deliveryDone = true;
            done(deliveryWrapper);
        });

        nameInput.addEventListener('input', function() {
            if (this.value.trim().length >= 1) {
                goToStep(2);
            } else {
                goToStep(1);
            }
            checkAllDone();
        });

        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
            if (this.value.length === 10 && /^0[567]/.test(this.value)) {
                goToStep(3);
            } else if (currentStep > 2) {
                goToStep(2);
            }
            checkAllDone();
        });

        wilayaSelect.addEventListener('change', function() {
            if (this.value) {
                updateCommunes();
                hideStopDeskWarning();
                deliveryDone = false;
                goToStep(4);
            } else {
                goToStep(3);
            }
            checkAllDone();
        });

        communeSelect.addEventListener('change', function() {
            if (this.value) {
                communeDone = true;
                goToStep(5);
                checkStopDeskAvailability();
                if (deliveryDone) {
                    done(deliveryWrapper);
                } else if (communeHasStopDesk || !stopDeskRadio.checked) {
                    deliveryDone = true;
                    done(deliveryWrapper);
                }
            } else {
                communeDone = false;
                deliveryDone = false;
                neutral(deliveryWrapper);
            }
            checkAllDone();
        });

        document.querySelectorAll('input[name="delivery_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                deliveryDone = true;
                done(deliveryWrapper);
                if (this.value === 'home') hideStopDeskWarning();
                if (this.value === 'stop_desk' && !communeHasStopDesk && communeSelect.value) {
                    showStopDeskWarning();
                }
                checkAllDone();
            });
        });

        function checkStopDeskAvailability() {
            var wilaya = wilayaSelect.value;
            var commune = communeSelect.value;
            communeHasStopDesk = false;

            if (wilaya && commune && stopDeskByWilaya[wilaya]) {
                var available = stopDeskByWilaya[wilaya];
                var communeLower = commune.toLowerCase().replace(/'/g, "'");
                communeHasStopDesk = available.some(function(c) {
                    return c.toLowerCase() === communeLower;
                });
            }

            if (!communeHasStopDesk && stopDeskRadio.checked) {
                showStopDeskWarning();
            } else if (communeHasStopDesk) {
                hideStopDeskWarning();
                stopDeskLabel.style.opacity = '1';
                stopDeskRadio.disabled = false;
            }
        }

        function showStopDeskWarning() {
            var wilaya = wilayaSelect.value;
            var commune = communeSelect.value;
            if (!wilaya || !commune || !stopDeskByWilaya[wilaya]) return;

            var available = stopDeskByWilaya[wilaya];
            stopDeskWarningText.innerHTML = 'هذه البلدية (<strong>' + commune + '</strong>) لا توفر مكتب توصيل. اختر مكتباً قريباً:';

            stopDeskSelect.innerHTML = '<option value="">-- اختر المكتب --</option>';
            available.forEach(function(c) {
                var opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                stopDeskSelect.appendChild(opt);
            });

            stopDeskDropdownContainer.classList.remove('hidden');
            stopDeskWarning.classList.remove('hidden');
            activate(stopDeskDropdownContainer);

            stopDeskLabel.style.opacity = '0.4';
            stopDeskRadio.disabled = true;
            homeRadio.checked = true;
            deliveryDone = false;
        }

        function hideStopDeskWarning() {
            stopDeskWarning.classList.add('hidden');
            stopDeskDropdownContainer.classList.add('hidden');
            neutral(stopDeskDropdownContainer);
            stopDeskCommuneInput.value = '';
            stopDeskLabel.style.opacity = '1';
            stopDeskRadio.disabled = false;
        }

        stopDeskSelect.addEventListener('change', function() {
            if (this.value) {
                stopDeskCommuneInput.value = this.value;
                done(stopDeskDropdownContainer);
                stopDeskLabel.style.opacity = '1';
                stopDeskRadio.disabled = false;
                stopDeskRadio.checked = true;
                deliveryDone = true;
                checkAllDone();
            } else {
                stopDeskCommuneInput.value = '';
                activate(stopDeskDropdownContainer);
                stopDeskLabel.style.opacity = '0.4';
                stopDeskRadio.disabled = true;
                homeRadio.checked = true;
                deliveryDone = false;
                checkAllDone();
            }
        });

        function updateCommunes() {
            var wilaya = wilayaSelect.value;
            communeSelect.innerHTML = '<option value="">البلدية</option>';
            if (wilaya && communesByWilaya[wilaya]) {
                communesByWilaya[wilaya].forEach(function(c) {
                    var opt = document.createElement('option');
                    opt.value = c;
                    opt.textContent = c;
                    communeSelect.appendChild(opt);
                });
            }
        }

        if (wilayaSelect.value) {
            updateCommunes();
            if (communeSelect.value) {
                checkStopDeskAvailability();
            }
        }
    </script>

</body>
</html>
