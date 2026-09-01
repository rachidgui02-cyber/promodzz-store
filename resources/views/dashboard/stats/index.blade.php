@extends('layouts.app')

@section('title', 'الإحصائيات')

@section('content')
<div class="space-y-6" dir="rtl">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold text-dark-100">الإحصائيات</h1>

    <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
        <form action="{{ route('dashboard.stats.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-dark-200 text-xs mb-1">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors">
            </div>
            <div class="flex-1">
                <label class="block text-dark-200 text-xs mb-1">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">تطبيق</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-dark-900 rounded-2xl p-5 border border-dark-800">
            <p class="text-dark-200 text-sm">إجمالي الإيرادات</p>
            <p class="text-2xl font-bold text-dark-100 mt-1">{{ number_format($revenueData['total'] ?? 0, 2) }} <span class="text-sm font-normal text-dark-200">DA</span></p>
        </div>
        <div class="bg-dark-900 rounded-2xl p-5 border border-dark-800">
            <p class="text-dark-200 text-sm">إجمالي الطلبات</p>
            <p class="text-2xl font-bold text-dark-100 mt-1">{{ $revenueData['total_orders'] ?? 0 }}</p>
        </div>
        <div class="bg-dark-900 rounded-2xl p-5 border border-dark-800">
            <p class="text-dark-200 text-sm">متوسط قيمة الطلب</p>
            <p class="text-2xl font-bold text-dark-100 mt-1">{{ number_format($revenueData['avg_order'] ?? 0, 2) }} <span class="text-sm font-normal text-dark-200">DA</span></p>
        </div>
        <div class="bg-dark-900 rounded-2xl p-5 border border-dark-800">
            <p class="text-dark-200 text-sm">معدل التحويل</p>
            <p class="text-2xl font-bold text-dark-100 mt-1">{{ $revenueData['conversion_rate'] ?? '0.0' }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
            <h2 class="text-lg font-bold text-dark-100 mb-4">الإيرادات (مخطط خطي)</h2>
            <div id="revenueChart" class="h-64 bg-dark-800 rounded-xl flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 text-dark-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-dark-400 text-sm">مخطط الإيرادات</p>
                    <p class="text-dark-500 text-xs mt-1">سيتم إضافته باستخدام Chart.js</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
            <h2 class="text-lg font-bold text-dark-100 mb-4">الطلبات حسب الحالة</h2>
            <div id="statusChart" class="h-64 bg-dark-800 rounded-xl flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 text-dark-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <p class="text-dark-400 text-sm">مخطط الحالات</p>
                    <p class="text-dark-500 text-xs mt-1">سيتم إضافته باستخدام Chart.js</p>
                </div>
            </div>
        </div>
    </div>

    @if(($statusBreakdown ?? collect())->count() > 0)
        <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
            <h2 class="text-lg font-bold text-dark-100 mb-4">توزيع الحالات</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $statusLabels = [
                        'new' => 'جديد',
                        'confirmed' => 'مؤكد',
                        'preparing' => 'قيد التجهيز',
                        'shipped' => 'تم الشحن',
                        'in_transit' => 'في الطريق',
                        'delivered' => 'تم التوصيل',
                        'returned' => 'مسترجع',
                        'cancelled' => 'ملغي',
                    ];
                    $statusColors = [
                        'new' => 'text-blue-400',
                        'confirmed' => 'text-indigo-400',
                        'preparing' => 'text-violet-400',
                        'shipped' => 'text-amber-400',
                        'in_transit' => 'text-orange-400',
                        'delivered' => 'text-emerald-400',
                        'returned' => 'text-rose-400',
                        'cancelled' => 'text-slate-400',
                    ];
                @endphp
                @foreach($statusBreakdown as $status => $count)
                    <div class="bg-dark-800 rounded-xl p-3 text-center">
                        <p class="text-dark-200 text-xs">{{ $statusLabels[$status] ?? $status }}</p>
                        <p class="text-lg font-bold {{ $statusColors[$status] ?? 'text-dark-100' }} mt-1">{{ $count }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-dark-900 rounded-2xl border border-dark-800">
        <div class="p-5 border-b border-dark-800">
            <h2 class="text-lg font-bold text-dark-100">أكثر المنتجات مبيعاً</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" dir="rtl">
                <thead>
                    <tr class="border-b border-dark-800 text-dark-200">
                        <th class="text-right px-5 py-3 font-medium">#</th>
                        <th class="text-right px-5 py-3 font-medium">المنتج</th>
                        <th class="text-right px-5 py-3 font-medium">الكمية المباعة</th>
                        <th class="text-right px-5 py-3 font-medium">إجمالي الإيرادات</th>
                        <th class="text-right px-5 py-3 font-medium">الربح</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts ?? [] as $index => $product)
                        <tr class="border-b border-dark-800/50 hover:bg-dark-800/30 transition-colors">
                            <td class="px-5 py-3 text-dark-200">{{ $index + 1 }}</td>
                            <td class="px-5 py-3 text-dark-100 font-medium">{{ $product->name }}</td>
                            <td class="px-5 py-3 text-dark-200">{{ $product->total_sold ?? 0 }}</td>
                            <td class="px-5 py-3 text-dark-100 font-bold">{{ number_format($product->total_revenue ?? 0, 2) }} DA</td>
                            <td class="px-5 py-3">
                                <span class="text-emerald-400 font-bold">{{ number_format($product->total_profit ?? 0, 2) }} DA</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-dark-200">لا توجد بيانات بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
