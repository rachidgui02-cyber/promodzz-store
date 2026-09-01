@extends('layouts.app')
@section('title', 'إحصائيات المكالمات')
@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:#111827;color:#ffffff">
            <svg class="w-6 h-6" style="color:var(--text-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">إحصائيات المكالمات</h1>
            <p class="text-sm" style="color:var(--text-secondary)">أداء المكالمات ونسبة التأكيد والتوصيل</p>
        </div>
    </div>
    @php
        $periods = [
            ['key' => 'today', 'label' => 'اليوم', 'stats' => $todayStats],
            ['key' => 'week', 'label' => 'هذا الأسبوع', 'stats' => $weekStats],
            ['key' => 'month', 'label' => 'هذا الشهر', 'stats' => $monthStats],
            ['key' => 'all', 'label' => 'الكل', 'stats' => $allTimeStats],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($periods as $period)
            @php $s = $period['stats']; @endphp
            <div class="stat-card rounded-2xl border p-5" style="border-color:var(--border)">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full" style="background:#4f8cff"></span>
                    <span class="text-sm font-bold" style="color:var(--text-primary)">{{ $period['label'] }}</span>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color:var(--text-secondary)">إجمالي الطلبات</span>
                        <span class="font-bold text-lg" style="color:var(--text-primary)">{{ $s['total'] }}</span>
                    </div>
                    <div class="border-t pt-3" style="border-color:var(--border)">
                        <div class="flex items-center justify-between mb-2"><span class="text-xs" style="color:var(--text-secondary)">تم الاتصال</span><span class="font-bold text-sm" style="color:#4f8cff">{{ $s['called'] }}</span></div>
                        <div class="flex items-center justify-between mb-2"><span class="text-xs" style="color:var(--text-secondary)">بانتظار المتابعة</span><span class="font-bold text-sm" style="color:#fb923c">{{ $s['pending'] }}</span></div>
                    </div>
                    <div class="border-t pt-3" style="border-color:var(--border)">
                        <div class="flex items-center justify-between mb-2"><span class="text-xs" style="color:var(--text-secondary)">نسبة التأكيد</span><span class="font-bold text-lg" style="color:#34d399">{{ $s['confirm_rate'] }}%</span></div>
                        <div class="w-full rounded-full h-2" style="background:rgba(255,255,255,0.06)"><div class="h-2 rounded-full transition-all" style="width:{{ $s['confirm_rate'] }}%;background:#34d399"></div></div>
                    </div>
                    <div class="border-t pt-3" style="border-color:var(--border)">
                        <div class="flex items-center justify-between mb-2"><span class="text-xs" style="color:var(--text-secondary)">نسبة التوصيل</span><span class="font-bold text-lg" style="color:#4f8cff">{{ $s['delivery_rate'] }}%</span></div>
                        <div class="w-full rounded-full h-2" style="background:rgba(255,255,255,0.06)"><div class="h-2 rounded-full transition-all" style="width:{{ $s['delivery_rate'] }}%;background:#4f8cff"></div></div>
                    </div>
                    <div class="border-t pt-3" style="border-color:var(--border)">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div><p class="font-bold text-sm" style="color:#34d399">{{ $s['delivered'] }}</p><p class="text-[10px]" style="color:var(--text-tertiary)">مُسلّم</p></div>
                            <div><p class="font-bold text-sm" style="color:#f87171">{{ $s['returned'] }}</p><p class="text-[10px]" style="color:var(--text-tertiary)">مرتجع</p></div>
                            <div><p class="font-bold text-sm" style="color:#6b7280">{{ $s['cancelled'] }}</p><p class="text-[10px]" style="color:var(--text-tertiary)">ملغي</p></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="px-5 py-4 border-b" style="border-color:var(--border)">
            <h3 class="font-bold text-sm" style="color:var(--text-primary)">آخر المكالمات المسجلة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03)">
                        <th class="text-right px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">رقم الطلب</th>
                        <th class="text-right px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                        <th class="text-right px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الولاية</th>
                        <th class="text-right px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المبلغ</th>
                        <th class="text-center px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المحاولات</th>
                        <th class="text-center px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحالة</th>
                        <th class="text-right px-4 py-3 font-semibold text-xs" style="color:var(--text-secondary)">آخر اتصال</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($recentCalls as $call)
                        @php
                            $dotColors = ['emerald' => '#34d399', 'green' => '#22c55e', 'amber' => '#fb923c', 'red' => '#f87171', 'violet' => '#a78bfa', 'blue' => '#4f8cff', 'orange' => '#fb923c', 'rose' => '#f472b6'];
                            $c = $dotColors[$call['status_color']] ?? '#6b7280';
                        @endphp
                        <tr class="transition-colors" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background=''">
                            <td class="px-4 py-3 font-mono text-xs font-bold" style="color:var(--text-primary)">#{{ $call['order_number'] }}</td>
                            <td class="px-4 py-3 text-xs" style="color:var(--text-primary)">{{ $call['customer_name'] }}</td>
                            <td class="px-4 py-3 text-xs" style="color:var(--text-secondary)">{{ $call['wilaya'] }}</td>
                            <td class="px-4 py-3 text-xs font-bold" style="color:var(--text-primary)">{{ number_format($call['total'], 0) }} <span style="color:var(--text-tertiary)">د.ج</span></td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold" style="background:rgba(251,146,60,0.12);color:#fb923c">{{ $call['call_attempts'] }} محاولة</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium" style="background:{{ $c }}15;color:{{ $c }};border:1px solid {{ $c }}30">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $c }}"></span>
                                    {{ $call['status_label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--text-secondary)">{{ $call['last_call_at'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-sm" style="color:var(--text-tertiary)">لا توجد مكالمات مسجلة بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
