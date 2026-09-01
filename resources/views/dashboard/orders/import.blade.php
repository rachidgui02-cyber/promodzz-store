@extends('layouts.app')

@section('title', 'استيراد طلبيات')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" dir="rtl">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('import_result'))
        <div class="bg-dark-900 rounded-2xl border border-dark-800 p-6">
            <h3 class="text-lg font-bold text-dark-100 mb-4">نتائج الاستيراد</h3>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="bg-emerald-500/10 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-400">{{ session('import_result.imported', 0) }}</div>
                    <div class="text-sm text-dark-200">تم استيرادها</div>
                </div>
                <div class="bg-blue-500/10 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-400">{{ session('import_result.sent_to_shipping', 0) }}</div>
                    <div class="text-sm text-dark-200">تم إرسالها للشحن</div>
                </div>
                <div class="bg-rose-500/10 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-rose-400">{{ session('import_result.failed', 0) }}</div>
                    <div class="text-sm text-dark-200">فشلت</div>
                </div>
            </div>
            @if(!empty(session('import_result.errors')))
                <div class="bg-dark-800 rounded-xl p-4">
                    <h4 class="text-rose-400 font-bold text-sm mb-2">الأخطاء:</h4>
                    <ul class="text-dark-200 text-sm space-y-1">
                        @foreach(session('import_result.errors') as $error)
                            <li class="flex items-start gap-2">
                                <span class="text-rose-400">•</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="flex items-center gap-3">
        <a href="{{ route('dashboard.orders.index') }}" class="w-10 h-10 rounded-xl bg-dark-800 border border-dark-700 flex items-center justify-center text-dark-200 hover:text-dark-100 hover:border-dark-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-dark-100">استيراد طلبيات</h1>
        <div class="mr-auto flex gap-2">
            <a href="{{ route('dashboard.orders.exportDhd') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                تصدير لـ DHD
            </a>
        </div>
    </div>

    <div class="bg-dark-900 rounded-2xl border border-dark-800 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-dark-100">تعليمات الرفع</h2>
                <p class="text-dark-200 text-sm">تأكد من تنسيق الملف بشكل صحيح قبل الرفع</p>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-dark-100 text-sm font-medium mb-3">نوع ملف CSV:</label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-3 p-4 bg-dark-800 rounded-xl border border-dark-700 cursor-pointer hover:border-blue-500/50 transition-colors">
                    <input type="radio" name="format" value="youcan" checked class="w-4 h-4 text-blue-500 bg-dark-700 border-dark-600 focus:ring-blue-500 focus:ring-offset-0">
                    <div>
                        <div class="text-dark-100 font-medium text-sm">YouCan / Shopify</div>
                        <div class="text-dark-300 text-xs">الأعمدة العربية</div>
                    </div>
                </label>
                <label class="flex-1 flex items-center gap-3 p-4 bg-dark-800 rounded-xl border border-dark-700 cursor-pointer hover:border-amber-500/50 transition-colors">
                    <input type="radio" name="format" value="dhd" class="w-4 h-4 text-amber-500 bg-dark-700 border-dark-600 focus:ring-amber-500 focus:ring-offset-0">
                    <div>
                        <div class="text-dark-100 font-medium text-sm">DHD Livraison</div>
                        <div class="text-dark-300 text-xs">الفورمة الرسمية</div>
                    </div>
                </label>
            </div>
        </div>

        <div id="youcan-format" class="bg-dark-800 rounded-xl p-4 mb-6">
            <h3 class="text-dark-100 font-bold text-sm mb-3">أعمدة YouCan:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    اسم العميل
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    رقم الهاتف
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    ولاية الشحن
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">اختياري</span>
                    مدينة الشحن
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">اختياري</span>
                    عنوان المنتج
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">اختياري</span>
                    الكمية, سعر الوحدة, السعر الإجمالي
                </div>
            </div>
        </div>

        <div id="dhd-format" class="bg-dark-800 rounded-xl p-4 mb-6 hidden">
            <h3 class="text-amber-400 font-bold text-sm mb-3">أعمدة DHD الرسمية:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    reference commande - رقم الطلب
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    nom et prenom du destinataire* - اسم العميل
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    telephone* - رقم الهاتف
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    code wilaya* - رقم الولاية
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    commune de livraison* - البلدية
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    adresse de livraison* - العنوان
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    produit* - المنتج
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">م</span>
                    montant du colis* - المبلغ الإجمالي
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">اختياري</span>
                    poids (kg) - الوزن
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">اختياري</span>
                    remarque - ملاحظات
                </div>
                <div class="flex items-center gap-2 text-sm text-dark-200">
                    <span class="w-5 h-5 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">اختياري</span>
                    FRAGILE / STOP DESK / ECHANGE
                </div>
            </div>
        </div>

        <form action="{{ route('dashboard.orders.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="format" id="format_input" value="youcan">

            <div>
                <label class="block text-dark-100 text-sm font-medium mb-2">اختر ملف CSV</label>
                <div class="relative">
                    <input type="file" name="csv_file" accept=".csv" required class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-3 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors file:ml-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer file:transition-colors">
                </div>
                @error('csv_file')
                    <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-3 p-4 bg-dark-800 rounded-xl border border-blue-500/20">
                <input type="checkbox" name="send_to_shipping" value="1" id="send_to_shipping" class="w-4 h-4 rounded border-dark-600 bg-dark-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-0">
                <label for="send_to_shipping" class="text-dark-100 text-sm">
                    إرسال الطلبيات للشحن تلقائياً بعد الاستيراد (DHD)
                </label>
            </div>

            <button type="submit" onclick="return confirm('هل أنت متأكد من رفع هذا الملف؟ سيتم إنشاء طلبات جديدة.')" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                رفع الملف وإنشاء الطلبات
            </button>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('input[name="format"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('format_input').value = this.value;
        document.getElementById('youcan-format').classList.toggle('hidden', this.value !== 'youcan');
        document.getElementById('dhd-format').classList.toggle('hidden', this.value !== 'dhd');
    });
});
</script>
@endsection
