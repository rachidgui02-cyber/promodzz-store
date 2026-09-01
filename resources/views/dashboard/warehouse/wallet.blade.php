@extends('layouts.app')
@section('title', 'المحفظة')
@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.warehouse.index') }}" class="p-2 rounded-lg transition-colors" style="color:var(--text-secondary)" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg></a>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">المحفظة</h1>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden" style="border-color:var(--border)">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-blue to-accent-purple"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(79,140,255,0.12)"><svg class="w-5 h-5" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg></div>
            <div class="text-xs font-medium mb-1" style="color:var(--text-secondary)">إجمالي الإيرادات</div>
            <div class="text-2xl font-extrabold" style="color:#4f8cff">{{ number_format($totalRevenue) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">{{ $totalOrders }} طلبية</div>
        </div>
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden" style="border-color:var(--border)">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-green to-accent-teal"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(52,211,153,0.12)"><svg class="w-5 h-5" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg></div>
            <div class="text-xs font-medium mb-1" style="color:var(--text-secondary)">المبلغ المحصّل (مُسلّم)</div>
            <div class="text-2xl font-extrabold" style="color:#34d399">{{ number_format($deliveredPaid) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">طلبيات مسلّمة تم الدفع</div>
        </div>
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden" style="border-color:var(--border)">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-orange to-accent-yellow"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(251,146,60,0.12)"><svg class="w-5 h-5" style="color:#fb923c" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="text-xs font-medium mb-1" style="color:var(--text-secondary)">المبلغ المستحق (COD)</div>
            <div class="text-2xl font-extrabold" style="color:#fb923c">{{ number_format($deliveredUnpaid) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">طلبيات مسلّمة — في انتظار التحصيل</div>
        </div>
        <div class="surface-card rounded-2xl border p-5 relative overflow-hidden" style="border-color:var(--border)">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-red to-accent-pink"></div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(248,113,113,0.12)"><svg class="w-5 h-5" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg></div>
            <div class="text-xs font-medium mb-1" style="color:var(--text-secondary)">عدد الوحدات المباعة</div>
            <div class="text-2xl font-extrabold" style="color:var(--text-primary)">{{ number_format($totalUnits) }}</div>
            <div class="text-xs mt-1" style="color:var(--text-tertiary)">{{ number_format($deliveredUnits) }} وحدة مسلّمة</div>
        </div>
    </div>

    {{-- Recent Payouts Table --}}
    <div class="surface-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="px-5 py-4 border-b" style="border-color:var(--border)">
            <h3 class="text-sm font-bold" style="color:var(--text-primary)">آخر الطلبيات المسلّمة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:var(--table-header-bg)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">رقم الطلبية</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الهاتف</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">السعر</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الشحن</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الإجمالي</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">التاريخ</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($payouts as $order)
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-5 py-3"><span class="font-bold" style="color:#4f8cff">{{ $order->order_number }}</span></td>
                            <td class="px-5 py-3 text-sm font-medium" style="color:var(--text-primary)">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-secondary)" dir="ltr">{{ $order->customer_phone }}</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-primary)">{{ number_format($order->total) }} DA</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-primary)">{{ number_format($order->shipping_cost) }} DA</td>
                            <td class="px-5 py-3 font-bold" style="color:#34d399">{{ number_format($order->total + $order->shipping_cost) }} DA</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-secondary)">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد طليبات مسلّمة بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->hasPages())<div class="px-4 py-3 border-t" style="border-color:var(--border)">{{ $payouts->links() }}</div>@endif
    </div>
</div>
@endsection
