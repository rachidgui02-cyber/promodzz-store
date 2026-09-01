@extends('layouts.app')
@section('title', 'لوحة القيادة')
@section('content')
<div class="space-y-6" dir="rtl" data-poll-orders>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">لوحة القيادة</h1>
            <p class="text-sm mt-1" style="color:var(--text-secondary)">مرحباً بك في {{ $shop->name ?? 'Mega.Market' }}</p>
        </div>
        <div class="text-sm font-medium" style="color:var(--text-secondary)">{{ now()->format('l, d F Y') }}</div>
    </div>

    {{-- ═══════ Main Stat Cards ═══════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(52,211,153,0.1)">
                    <svg class="w-5 h-5" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">مخزن</span>
                    <div class="text-xl font-extrabold" style="color:#34d399">{{ number_format($stats['warehouse'] ?? 0) }} <span class="text-xs font-normal" style="color:var(--text-tertiary)">DA</span></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(79,140,255,0.1)">
                    <svg class="w-5 h-5" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">في الطريق</span>
                    <div class="text-xl font-extrabold" style="color:#4f8cff">{{ number_format($stats['in_transit'] ?? 0) }} <span class="text-xs font-normal" style="color:var(--text-tertiary)">DA</span></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(251,146,60,0.1)">
                    <svg class="w-5 h-5" style="color:#fb923c" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">كاش معلق</span>
                    <div class="text-xl font-extrabold" style="color:#fb923c">{{ number_format($stats['pending_cash'] ?? 0) }} <span class="text-xs font-normal" style="color:var(--text-tertiary)">DA</span></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(167,139,250,0.1)">
                    <svg class="w-5 h-5" style="color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">صافي القيمة</span>
                    <div class="text-xl font-extrabold" style="color:#a78bfa">{{ number_format($stats['net_worth'] ?? 0) }} <span class="text-xs font-normal" style="color:var(--text-tertiary)">DA</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════ Secondary Stat Cards ═══════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="stat-card text-center">
            <div class="text-2xl font-extrabold" style="color:var(--text-primary)">{{ $stats['total_orders'] ?? 0 }}</div>
            <div class="text-xs font-medium mt-1" style="color:var(--text-secondary)">إجمالي الطلبات</div>
        </div>
        <div class="stat-card text-center">
            <div class="text-2xl font-extrabold" style="color:#4f8cff">{{ $stats['dhd_parcels'] ?? 0 }}</div>
            <div class="text-xs font-medium mt-1" style="color:var(--text-secondary)">الطرود (DHD)</div>
        </div>
        <div class="stat-card text-center">
            <div class="text-2xl font-extrabold" style="color:#34d399">{{ $stats['in_delivery'] ?? 0 }}</div>
            <div class="text-xs font-medium mt-1" style="color:var(--text-secondary)">قيد التوصيل</div>
        </div>
        <div class="stat-card text-center">
            <div class="text-2xl font-extrabold" style="color:#2dd4bf">{{ $stats['delivered'] ?? 0 }}</div>
            <div class="text-xs font-medium mt-1" style="color:var(--text-secondary)">تم التوصيل</div>
        </div>
    </div>

    {{-- ═══════ Visitor Stats ═══════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(99,102,241,0.1)">
                    <svg class="w-4 h-4" style="color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <span class="text-xs font-medium" style="color:var(--text-secondary)">زوار اليوم</span>
            </div>
            <div class="text-2xl font-extrabold" style="color:#6366f1">{{ number_format($visitorStats['unique_visitors'] ?? 0) }}</div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">{{ number_format($visitorStats['total_visitors'] ?? 0) }} زيارة</div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(16,185,129,0.1)">
                    <svg class="w-4 h-4" style="color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <span class="text-xs font-medium" style="color:var(--text-secondary)">معدل التحويل</span>
            </div>
            @php
                $visitorCount = max($visitorStats['unique_visitors'] ?? 1, 1);
                $orderCount = $stats['total_orders'] ?? 0;
                $conversionRate = round(($orderCount / $visitorCount) * 100, 1);
            @endphp
            <div class="text-2xl font-extrabold" style="color:#10b981">{{ $conversionRate }}%</div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">{{ $orderCount }} طلب / {{ $visitorCount }} زائر</div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(245,158,11,0.1)">
                    <svg class="w-4 h-4" style="color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                </div>
                <span class="text-xs font-medium" style="color:var(--text-secondary)">محمول</span>
            </div>
            <div class="text-2xl font-extrabold" style="color:#f59e0b">{{ $visitorStats['devices']['mobile'] ?? 0 }}</div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">{{ $visitorStats['devices']['desktop'] ?? 0 }} سطح مكتب</div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(236,72,153,0.1)">
                    <svg class="w-4 h-4" style="color:#ec4899" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                </div>
                <span class="text-xs font-medium" style="color:var(--text-secondary)">متوسط يومي</span>
            </div>
            @php
                $avgPerDay = $weeklyVisitors->count() > 0 ? round($weeklyVisitors->sum('unique_count') / 7) : 0;
            @endphp
            <div class="text-2xl font-extrabold" style="color:#ec4899">{{ number_format($avgPerDay) }}</div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">متوسط يومي (7 أيام)</div>
        </div>
    </div>

    {{-- ═══════ DHD / Ecotrack Stats ═══════ --}}
    <div class="stat-card overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between flex-wrap gap-2" style="border-color:var(--divider)">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                <h2 class="text-sm font-bold" style="color:var(--text-primary)">إحصائيات الشحن (DHD / Ecotrack)</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background:{{ ($dhdConfig['mock_mode'] ?? true) ? 'rgba(251,146,60,0.12)' : 'rgba(52,211,153,0.12)' }};color:{{ ($dhdConfig['mock_mode'] ?? true) ? '#fb923c' : '#34d399' }}">
                    {{ ($dhdConfig['mock_mode'] ?? true) ? 'وضع المحاكاة' : 'متصل' }}
                </span>
                <a href="{{ route('dashboard.shipping.index') }}" class="text-xs font-bold transition-colors" style="color:#4f8cff">إدارة الشحن</a>
            </div>
        </div>
        <div class="p-5 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card text-center">
                <div class="text-2xl font-extrabold" style="color:#4f8cff">{{ $stats['dhd_parcels'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:var(--text-secondary)">إجمالي الطرود المُرسلة</div>
            </div>
            <div class="stat-card text-center">
                <div class="text-2xl font-extrabold" style="color:#2dd4bf">{{ $stats['dhd_in_transit'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:var(--text-secondary)">في الطريق (Livraison)</div>
            </div>
            <div class="stat-card text-center">
                <div class="text-2xl font-extrabold" style="color:#34d399">{{ $stats['dhd_delivered'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:var(--text-secondary)">تم التوصيل (Livré)</div>
            </div>
            <div class="stat-card text-center">
                <div class="text-2xl font-extrabold" style="color:#f87171">{{ $stats['dhd_returned'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:var(--text-secondary)">مرتجع (Retour)</div>
            </div>
        </div>
    </div>

    {{-- ═══════ Recent Orders Table ═══════ --}}
    <div class="stat-card overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:var(--divider)">
            <h2 class="text-sm font-bold" style="color:var(--text-primary)">آخر 10 طلبات</h2>
            <a href="{{ route('dashboard.orders.index') }}" class="text-xs font-bold transition-colors" style="color:#4f8cff">عرض الكل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:var(--table-header-bg)">
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">رقم الطلب</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الهاتف</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المبلغ</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحالة</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">التاريخ</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--divider)">
                    @forelse($recentOrders ?? [] as $order)
                        @php
                            $colorMap = [
                                'new' => '#4f8cff', 'confirmed' => '#a78bfa', 'processing' => '#818cf8',
                                'shipped' => '#fb923c', 'out_for_delivery' => '#2dd4bf', 'delivered' => '#34d399',
                                'returned' => '#f87171', 'cancelled' => '#6b7280',
                                'waiting_callback' => '#fbbf24', 'customer_unavailable' => '#f97316',
                                'no_answer_1' => '#fb923c', 'no_answer_2' => '#f97316', 'no_answer_3' => '#f87171',
                            ];
                            $labelMap = [
                                'new' => 'جديد', 'confirmed' => 'مؤكد', 'processing' => 'قيد التجهيز',
                                'shipped' => 'شُحن', 'out_for_delivery' => 'في التوصيل', 'delivered' => 'مُسلّم',
                                'returned' => 'مرتجع', 'cancelled' => 'ملغي',
                                'waiting_callback' => 'بانتظار المكالمة', 'customer_unavailable' => 'العميل غير متاح',
                                'no_answer_1' => 'لم يجب (1)', 'no_answer_2' => 'لم يجب (2)', 'no_answer_3' => 'لم يجب (3)',
                            ];
                            $c = $colorMap[$order->status] ?? '#6b7280';
                        @endphp
                        <tr class="transition-colors" style="--tw-bg-opacity:1" onmouseover="this.style.background=var(--hover-bg)" onmouseout="this.style.background='transparent'">
                            <td class="px-5 py-3 font-mono font-bold" style="color:var(--text-primary)">#{{ $order->order_number }}</td>
                            <td class="px-5 py-3" style="color:var(--text-primary)">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)" dir="ltr">{{ $order->customer_phone }}</td>
                            <td class="px-5 py-3 font-bold" style="color:var(--text-primary)">{{ number_format($order->total) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:{{ $c }}20;color:{{ $c }}">{{ $labelMap[$order->status] ?? $order->status }}</span>
                            </td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 rounded-lg transition-colors" style="color:var(--text-secondary)" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='transparent'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-44 rounded-xl shadow-2xl z-10 py-1" style="background:var(--surface-card);border:1px solid var(--border)">
                                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="block px-4 py-2 text-sm transition-colors" style="color:var(--text-secondary)" onmouseover="this.style.background='var(--hover-bg)';this.style.color='var(--text-primary)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">عرض التفاصيل</a>
                                        <a href="{{ route('dashboard.orders.label', $order->id) }}" class="block px-4 py-2 text-sm transition-colors" style="color:var(--text-secondary)" onmouseover="this.style.background='var(--hover-bg)';this.style.color='var(--text-primary)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">طباعة الملصق</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد طلبات بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════ Low Stock Alert ═══════ --}}
    @if(($lowStockProducts ?? collect())->count() > 0)
        <div class="stat-card overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center gap-2" style="border-color:var(--divider)">
                <svg class="w-5 h-5" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <h2 class="text-sm font-bold" style="color:var(--text-primary)">تنبيه المخزون المنخفض</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($lowStockProducts as $product)
                        <div class="rounded-xl p-4 flex items-center justify-between transition-colors" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15)">
                            <div>
                                <p class="font-semibold text-sm" style="color:var(--text-primary)">{{ $product->name }}</p>
                                <p class="text-xs mt-1" style="color:var(--text-secondary)">المخزون: <span class="font-bold" style="color:#f87171">{{ $product->stock_quantity }}</span> | الحد الأدنى: {{ $product->low_stock_threshold }}</p>
                            </div>
                            <a href="{{ route('dashboard.products.edit', $product->id) }}" class="text-xs font-bold transition-colors" style="color:#4f8cff">تعديل</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
