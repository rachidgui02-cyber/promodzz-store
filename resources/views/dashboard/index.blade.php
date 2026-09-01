@extends('layouts.app')
@section('title', 'لوحة القيادة')
@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-white">لوحة القيادة</h1>
            <p class="text-sm mt-1" style="color:#8a92a6">مرحباً بك في {{ $shop->name ?? 'Mega.Market' }}</p>
        </div>
        <div class="text-sm font-medium" style="color:#8a92a6">{{ now()->format('l, d F Y') }}</div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden hover:border-accent-blue/30 transition-colors">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-blue to-accent-purple"></div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(79,140,255,0.12)">
                    <svg class="w-5 h-5" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:#8a92a6">مخزن</span>
                    <div class="text-xl font-extrabold text-white">{{ number_format($stats['warehouse'] ?? 0) }} <span class="text-xs font-normal" style="color:#555a6e">DA</span></div>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden hover:border-accent-teal/30 transition-colors">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-teal to-accent-blue"></div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(45,212,191,0.12)">
                    <svg class="w-5 h-5" style="color:#2dd4bf" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:#8a92a6">في الطريق</span>
                    <div class="text-xl font-extrabold text-white">{{ number_format($stats['in_transit'] ?? 0) }} <span class="text-xs font-normal" style="color:#555a6e">DA</span></div>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden hover:border-accent-orange/30 transition-colors">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-orange to-accent-yellow"></div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(251,146,60,0.12)">
                    <svg class="w-5 h-5" style="color:#fb923c" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:#8a92a6">كاش معلق</span>
                    <div class="text-xl font-extrabold text-white">{{ number_format($stats['pending_cash'] ?? 0) }} <span class="text-xs font-normal" style="color:#555a6e">DA</span></div>
                </div>
            </div>
        </div>
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden hover:border-accent-purple/30 transition-colors">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-purple to-accent-pink"></div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(167,139,250,0.12)">
                    <svg class="w-5 h-5" style="color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:#8a92a6">صافي القيمة</span>
                    <div class="text-xl font-extrabold text-white">{{ number_format($stats['net_worth'] ?? 0) }} <span class="text-xs font-normal" style="color:#555a6e">DA</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="surface-card rounded-2xl border p-4 text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-2" style="background:#8a92a6"></div>
            <div class="text-xs font-medium mb-1" style="color:#8a92a6">إجمالي الطلبات</div>
            <div class="text-xl font-extrabold text-white">{{ $stats['total_orders'] ?? 0 }}</div>
        </div>
        <div class="surface-card rounded-2xl border p-4 text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-2" style="background:#4f8cff"></div>
            <div class="text-xs font-medium mb-1" style="color:#8a92a6">الطرود (DHD)</div>
            <div class="text-xl font-extrabold" style="color:#4f8cff">{{ $stats['dhd_parcels'] ?? 0 }}</div>
        </div>
        <div class="surface-card rounded-2xl border p-4 text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-2" style="background:#2dd4bf"></div>
            <div class="text-xs font-medium mb-1" style="color:#8a92a6">قيد التوصيل</div>
            <div class="text-xl font-extrabold" style="color:#2dd4bf">{{ $stats['in_delivery'] ?? 0 }}</div>
        </div>
        <div class="surface-card rounded-2xl border p-4 text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-2" style="background:#34d399"></div>
            <div class="text-xs font-medium mb-1" style="color:#8a92a6">تم التوصيل</div>
            <div class="text-xl font-extrabold" style="color:#34d399">{{ $stats['delivered'] ?? 0 }}</div>
        </div>
    </div>

    {{-- ═══════ DHD / Livraison Statistics ═══════ --}}
    <div class="surface-card rounded-2xl border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between flex-wrap gap-2" style="border-color:#232530">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                <h2 class="text-sm font-bold text-white">إحصائيات الشحن (DHD / Ecotrack)</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background:{{ ($dhdConfig['mock_mode'] ?? true) ? 'rgba(251,146,60,0.12)' : 'rgba(52,211,153,0.12)' }};color:{{ ($dhdConfig['mock_mode'] ?? true) ? '#fb923c' : '#34d399' }}">
                    {{ ($dhdConfig['mock_mode'] ?? true) ? 'وضع المحاكاة' : 'متصل' }}
                </span>
                <a href="{{ route('dashboard.shipping.index') }}" class="text-xs font-bold transition-colors" style="color:#4f8cff">إدارة الشحن</a>
            </div>
        </div>
        <div class="p-5 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-xl p-4 text-center" style="background:rgba(79,140,255,0.06);border:1px solid rgba(79,140,255,0.12)">
                <div class="text-2xl font-extrabold" style="color:#4f8cff">{{ $stats['dhd_parcels'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:#8a92a6">إجمالي الطرود المُرسلة</div>
            </div>
            <div class="rounded-xl p-4 text-center" style="background:rgba(45,212,191,0.06);border:1px solid rgba(45,212,191,0.12)">
                <div class="text-2xl font-extrabold" style="color:#2dd4bf">{{ $stats['dhd_in_transit'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:#8a92a6">في الطريق (Livraison)</div>
            </div>
            <div class="rounded-xl p-4 text-center" style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.12)">
                <div class="text-2xl font-extrabold" style="color:#34d399">{{ $stats['dhd_delivered'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:#8a92a6">تم التوصيل (Livré)</div>
            </div>
            <div class="rounded-xl p-4 text-center" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.12)">
                <div class="text-2xl font-extrabold" style="color:#f87171">{{ $stats['dhd_returned'] ?? 0 }}</div>
                <div class="text-xs mt-1 font-medium" style="color:#8a92a6">مرتجع (Retour)</div>
            </div>
        </div>
    </div>

    <div class="surface-card rounded-2xl border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#232530">
            <h2 class="text-sm font-bold text-white">آخر 10 طلبات</h2>
            <a href="{{ route('dashboard.orders.index') }}" class="text-xs font-bold transition-colors" style="color:#4f8cff">عرض الكل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03)">
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">رقم الطلب</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">العميل</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">الهاتف</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">المبلغ</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">الحالة</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">التاريخ</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#232530">
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
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-5 py-3 font-mono font-bold text-white">#{{ $order->order_number }}</td>
                            <td class="px-5 py-3 text-white">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3" style="color:#8a92a6" dir="ltr">{{ $order->customer_phone }}</td>
                            <td class="px-5 py-3 font-bold text-white">{{ number_format($order->total) }} <span class="text-xs" style="color:#555a6e">DA</span></td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:{{ $c }}20;color:{{ $c }}">{{ $labelMap[$order->status] ?? $order->status }}</span>
                            </td>
                            <td class="px-5 py-3" style="color:#8a92a6">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 rounded-lg transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-44 rounded-xl shadow-2xl z-10 py-1" style="background:#1a1c25;border:1px solid #232530">
                                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="block px-4 py-2 text-sm transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#8a92a6'">عرض التفاصيل</a>
                                        <a href="{{ route('dashboard.orders.label', $order->id) }}" class="block px-4 py-2 text-sm transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#8a92a6'">طباعة الملصق</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center" style="color:#555a6e">لا توجد طلبات بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(($lowStockProducts ?? collect())->count() > 0)
        <div class="surface-card rounded-2xl border overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center gap-2" style="border-color:#232530">
                <svg class="w-5 h-5" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <h2 class="text-sm font-bold text-white">تنبيه المخزون المنخفض</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($lowStockProducts as $product)
                        <div class="rounded-xl p-4 flex items-center justify-between transition-colors" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15)">
                            <div>
                                <p class="font-semibold text-sm text-white">{{ $product->name }}</p>
                                <p class="text-xs mt-1" style="color:#8a92a6">المخزون: <span class="font-bold" style="color:#f87171">{{ $product->stock_quantity }}</span> | الحد الأدنى: {{ $product->low_stock_threshold }}</p>
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
