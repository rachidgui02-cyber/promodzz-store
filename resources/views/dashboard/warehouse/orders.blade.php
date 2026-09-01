@extends('layouts.app')
@section('title', 'طلبيات المخزون')
@section('content')
<div class="space-y-6" dir="rtl" x-data="{ showModal: false, timeline: [], loading: false, searchQuery: '{{ request('search') }}' }">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.warehouse.index') }}" class="p-2 rounded-lg transition-colors" style="color:var(--text-secondary)" onmouseover="this.style.background=var(--hover-bg)" onmouseout="this.style.background='transparent'"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg></a>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">طلبيات المخزون</h1>
        </div>
        <div class="flex items-center gap-2">
            @foreach(['all'=>'الكل','today'=>'اليوم','7days'=>'7 أيام','30days'=>'30 يوم'] as $key => $label)
                <a href="?period={{ $key }}&search={{ request('search') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all" style="{{ request('period','all') === $key ? 'background:#111827;color:#ffffff' : 'background:var(--input-bg);color:var(--text-secondary)' }}">{{ $label }}</a>
            @endforeach
            <form method="GET" class="flex items-center gap-2" x-data>
                <input type="text" name="search" x-model="searchQuery" placeholder="بحث بالاسم، الهاتف، الرقم..." class="px-3 py-2 rounded-xl text-sm font-medium" style="background:var(--input-bg);color:var(--text-primary);border:1px solid var(--border)" @keyup.enter="$el.form.submit()">
                <button type="submit" class="p-2 rounded-xl" style="background:rgba(79,140,255,0.12);color:#4f8cff"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:var(--table-header-bg)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الرقم</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الهاتف</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الولاية</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الإجمالي</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحالة</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">التاريخ</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($orders as $order)
                        <tr class="transition-colors" onmouseover="this.style.background=var(--hover-bg)" onmouseout="this.style.background='transparent'">
                            <td class="px-5 py-3"><span class="font-bold" style="color:#4f8cff">{{ $order->order_number }}</span></td>
                            <td class="px-5 py-3 text-sm font-medium" style="color:var(--text-primary)">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-secondary)" dir="ltr">{{ $order->customer_phone }}</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-primary)">{{ $order->wilaya }}</td>
                            <td class="px-5 py-3 font-bold" style="color:var(--text-primary)">{{ number_format($order->total + $order->shipping_cost) }} DA</td>
                            <td class="px-5 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:{{ \App\Models\Order::getStatusHexColor($order->status) }}20;color:{{ \App\Models\Order::getStatusHexColor($order->status) }}">{{ \App\Models\Order::getStatusLabel($order->status) }}</span></td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-secondary)">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                <button @click="loading = true; showModal = true; fetch('{{ route('dashboard.warehouse.orderTimeline', $order->id) }}').then(r=>r.json()).then(d=>{ timeline = d; loading = false; })" class="p-2 rounded-lg transition-colors" style="color:#4f8cff" onmouseover="this.style.background='rgba(79,140,255,0.12)'" onmouseout="this.style.background='transparent'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد طليبات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())<div class="px-4 py-3 border-t" style="border-color:var(--border)">{{ $orders->withQueryString()->links() }}</div>@endif
    </div>

    {{-- Timeline Modal --}}
    <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div @click.away="showModal = false" x-show="showModal" x-transition class="modal-light rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto" x-cloak>
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-extrabold" style="color:#1a1a2e">تسلسل زمني — <span x-text="timeline.order?.order_number" style="color:#4f8cff"></span></h3>
                    <button @click="showModal = false" class="p-1 rounded-lg" style="color:#9ca3af"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <template x-if="loading"><div class="flex items-center justify-center py-10"><div class="w-8 h-8 rounded-full animate-spin" style="border:3px solid #e5e7eb;border-top-color:#4f8cff"></div></div></template>

                <template x-if="!loading && timeline.order">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl p-3" style="background:#f1f3f8"><div class="text-xs" style="color:#9ca3af">العميل</div><div class="font-bold text-sm" style="color:#1a1a2e" x-text="timeline.order.customer_name"></div></div>
                            <div class="rounded-xl p-3" style="background:#f1f3f8"><div class="text-xs" style="color:#9ca3af">الهاتف</div><div class="font-bold text-sm" style="color:#1a1a2e" x-text="timeline.order.customer_phone"></div></div>
                            <div class="rounded-xl p-3" style="background:#f1f3f8"><div class="text-xs" style="color:#9ca3af">الولاية</div><div class="font-bold text-sm" style="color:#1a1a2e" x-text="timeline.order.wilaya"></div></div>
                            <div class="rounded-xl p-3" style="background:#f1f3f8"><div class="text-xs" style="color:#9ca3af">الإجمالي</div><div class="font-bold text-sm" style="color:#1a1a2e" x-text="timeline.order.total + ' DA'"></div></div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold mb-3" style="color:#374151">التسلسل الزمني</h4>
                            <div class="space-y-3">
                                <template x-for="(entry, i) in timeline.history" :key="i">
                                    <div class="flex gap-3">
                                        <div class="flex flex-col items-center">
                                            <div class="w-3 h-3 rounded-full" :style="'background:' + entry.color"></div>
                                            <div class="w-0.5 h-full" style="background:#e5e7eb" x-show="i < timeline.history.length - 1"></div>
                                        </div>
                                        <div class="pb-4">
                                            <div class="text-sm font-bold" :style="'color:' + entry.color" x-text="entry.label"></div>
                                            <div class="text-xs mt-0.5" style="color:#9ca3af" x-text="entry.timestamp"></div>
                                            <div class="text-xs mt-1" style="color:#6b7280" x-show="entry.notes" x-text="'ملاحظات: ' + entry.notes"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
