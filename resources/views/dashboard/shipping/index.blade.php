@extends('layouts.app')
@section('title', 'الشحن')
@section('content')
<div class="space-y-6" dir="rtl" x-data="{ showCompanyModal: false }">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">الشحن</h1>
        <form action="{{ route('dashboard.orders.sync') }}" method="POST" x-data="{ syncing: false }" @submit="syncing = true">
            @csrf
            <button type="submit" :disabled="syncing" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed" style="color:var(--text-primary);background:linear-gradient(135deg,#34d399,#2dd4bf)">
                <svg x-show="syncing" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                <svg x-show="!syncing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span x-show="!syncing">مزامنة الطلبات</span>
                <span x-show="syncing">جاري المزامنة...</span>
            </button>
        </form>
    </div>
    @if(!empty($dhdConfig['token']))
    <div class="rounded-2xl p-4 flex items-center gap-3" style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.15)">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(52,211,153,0.12)"><svg class="w-5 h-5" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
        <div class="flex-1"><p class="text-sm font-bold" style="color:#34d399">DHD Livraison متصل</p><p class="text-xs" style="color:rgba(52,211,153,0.6)">الإرسال التلقائي: {{ ($dhdConfig['auto_send'] ?? '0') === '1' ? 'مفعّل' : 'معطّل' }}</p></div>
    </div>
    @else
    <div class="rounded-2xl p-4 flex items-center gap-3" style="background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.15)">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(251,191,36,0.12)"><svg class="w-5 h-5" style="color:#fbbf24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg></div>
        <div class="flex-1"><p class="text-sm font-bold" style="color:#fbbf24">DHD Livraison غير متصل</p><p class="text-xs" style="color:rgba(251,191,36,0.6)">أضف API Token من الإعدادات لتفعيل الشحن التلقائي</p></div>
    </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($companies ?? [] as $company)
            <div class="stat-card rounded-2xl border p-5 transition-colors" style="border-color:var(--border)">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold" style="color:var(--text-primary)">{{ $company->name }}</h3>
                    <form action="{{ route('dashboard.shipping.toggle', $company->id) }}" method="POST">@csrf @method('PATCH')
                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" style="background:{{ $company->is_active ? '#34d399' : '#33353f' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $company->is_active ? 'translate-x-1' : 'translate-x-6' }}"/>
                        </button>
                    </form>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm"><span style="color:var(--text-secondary)">التكلفة الأساسية</span><span style="color:var(--text-primary)">{{ number_format($company->base_cost, 2) }} DA</span></div>
                    <div class="flex justify-between text-sm"><span style="color:var(--text-secondary)">التكلفة/منتج</span><span style="color:var(--text-primary)">{{ number_format($company->per_item_cost, 2) }} DA</span></div>
                    <div class="flex justify-between text-sm"><span style="color:var(--text-secondary)">أيام التوصيل</span><span style="color:var(--text-primary)">{{ $company->estimated_days }} أيام</span></div>
                </div>
                <div class="mt-4 pt-3 border-t" style="border-color:var(--border)">
                    @if($company->is_active)<span class="text-xs font-medium" style="color:#34d399">نشط</span>@else<span class="text-xs font-medium" style="color:#6b7280">غير نشط</span>@endif
                </div>
            </div>
        @endforeach
        <div class="stat-card rounded-2xl border border-dashed p-5 flex flex-col items-center justify-center cursor-pointer transition-colors hover:border-accent-blue/30" style="border-color:var(--border)" @click="showCompanyModal = true">
            <svg class="w-8 h-8 mb-2" style="color:var(--text-tertiary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="text-sm" style="color:var(--text-secondary)">إضافة شركة شحن</span>
        </div>
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:var(--border)">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(251,191,36,0.12)"><svg class="w-4 h-4" style="color:#fbbf24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div><h2 class="text-sm font-bold" style="color:var(--text-primary)">طلبات بانتظار الإرسال</h2><p class="text-xs" style="color:var(--text-secondary)">{{ ($pendingShipments ?? collect())->count() }} طلب بانتظار الشحن</p></div>
            </div>
            @if(($pendingShipments ?? collect())->count() > 0)
            <form action="{{ route('dashboard.orders.sendAll') }}" method="POST" x-data="{ sending: false }" @submit="sending = true">
                @csrf
                <button type="submit" :disabled="sending" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all disabled:opacity-50" style="color:var(--text-primary);background:linear-gradient(135deg,#34d399,#2dd4bf)">
                    <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    <span x-show="!sending">إرسال الكل للشحن</span>
                    <span x-show="sending">جاري الإرسال...</span>
                </button>
            </form>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:rgba(255,255,255,0.03)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">رقم الطلب</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الهاتف</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الولاية</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المبلغ</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المنتجات</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($pendingShipments ?? [] as $order)
                        <tr class="transition-colors" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background=''">
                            <td class="px-5 py-3 font-mono font-bold" style="color:var(--text-primary)">#{{ $order->order_number }}</td>
                            <td class="px-5 py-3" style="color:var(--text-primary)">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)" dir="ltr">{{ $order->customer_phone }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $order->wilaya }}</td>
                            <td class="px-5 py-3 font-bold" style="color:var(--text-primary)">{{ number_format($order->total, 0) }} DA</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $order->items->sum('quantity') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.orders.show', $order->id) }}" class="transition-colors" style="color:#4f8cff" title="عرض"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    <form action="{{ route('dashboard.orders.updateStatus', $order->id) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold transition-all hover:scale-[1.02]" style="color:var(--text-primary);background:linear-gradient(135deg,#34d399,#2dd4bf)"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> إرسال للشحن</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center" style="color:var(--text-tertiary)"><svg class="w-12 h-12 mx-auto mb-3" style="color:var(--border)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>لا توجد طلبات جديدة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="px-5 py-4 border-b flex items-center gap-3" style="border-color:var(--border)">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(79,140,255,0.12)"><svg class="w-4 h-4" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg></div>
            <div><h2 class="text-sm font-bold" style="color:var(--text-primary)">طلبات مُرسلة — بانتظار التوصيل</h2><p class="text-xs" style="color:var(--text-secondary)">{{ ($ordersByStatus['confirmed'] ?? collect())->count() }} طلب في الطريق</p></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:rgba(255,255,255,0.03)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">رقم الطلب</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الهاتف</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الولاية</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المبلغ</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">شركة الشحن</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($ordersByStatus['confirmed'] ?? [] as $order)
                        <tr class="transition-colors" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background=''">
                            <td class="px-5 py-3 font-mono font-bold" style="color:var(--text-primary)">#{{ $order->order_number }}</td>
                            <td class="px-5 py-3" style="color:var(--text-primary)">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)" dir="ltr">{{ $order->customer_phone }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $order->wilaya }}</td>
                            <td class="px-5 py-3 font-bold" style="color:var(--text-primary)">{{ number_format($order->total, 0) }} DA</td>
                            <td class="px-5 py-3">
                                @if($order->shipping_company)<span class="px-2 py-0.5 rounded-md text-xs font-medium" style="background:rgba(79,140,255,0.12);color:#4f8cff">{{ $order->shipping_company }}</span>@else<span style="color:var(--text-tertiary)">—</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.orders.show', $order->id) }}" class="transition-colors" style="color:#4f8cff"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    @if($order->tracking_number)<a href="https://suivi.ecotrack.dz/suivi/{{ $order->tracking_number }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-mono transition-colors" style="background:rgba(52,211,153,0.12);color:#34d399">{{ $order->tracking_number }}<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>@endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد طلبات في الطريق</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="px-5 py-4 border-b flex items-center gap-3" style="border-color:var(--border)">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(167,139,250,0.12)"><svg class="w-4 h-4" style="color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg></div>
            <div><h2 class="text-sm font-bold" style="color:var(--text-primary)">طلبات مشحونة</h2><p class="text-xs" style="color:var(--text-secondary)">{{ ($ordersByStatus['shipped'] ?? collect())->count() }} طلب مشحون</p></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:rgba(255,255,255,0.03)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">رقم الطلب</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">العميل</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الولاية</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المبلغ</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">شركة الشحن</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($ordersByStatus['shipped'] ?? [] as $order)
                        <tr class="transition-colors" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background=''">
                            <td class="px-5 py-3 font-mono font-bold" style="color:var(--text-primary)">#{{ $order->order_number }}</td>
                            <td class="px-5 py-3" style="color:var(--text-primary)">{{ $order->customer_name }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $order->wilaya }}</td>
                            <td class="px-5 py-3 font-bold" style="color:var(--text-primary)">{{ number_format($order->total, 0) }} DA</td>
                            <td class="px-5 py-3">@if($order->shipping_company)<span class="px-2 py-0.5 rounded-md text-xs font-medium" style="background:rgba(167,139,250,0.12);color:#a78bfa">{{ $order->shipping_company }}</span>@else<span style="color:var(--text-tertiary)">—</span>@endif</td>
                            <td class="px-5 py-3"><a href="{{ route('dashboard.orders.show', $order->id) }}" class="transition-colors" style="color:#4f8cff"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد طلبات مشحونة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div x-show="showCompanyModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div @click.away="showCompanyModal = false" x-show="showCompanyModal" x-transition class="modal-light rounded-2xl shadow-2xl w-full max-w-md" x-cloak>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-extrabold" style="color:#1a1a2e">إضافة شركة شحن</h3>
                    <button @click="showCompanyModal = false" class="p-1 rounded-lg" style="color:#9ca3af"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form action="{{ route('dashboard.shipping.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">اسم الشركة *</label><input type="text" name="name" required class="w-full rounded-xl px-4 py-3 text-sm font-medium" placeholder="اسم شركة الشحن">@error('name')<span class="text-sm mt-1 block" style="color:#f87171">{{ $message }}</span>@enderror</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">التكلفة الأساسية (DA)</label><input type="number" name="base_cost" step="0.01" min="0" value="0" class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">التكلفة/منتج (DA)</label><input type="number" name="per_item_cost" step="0.01" min="0" value="0" class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                    </div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">أيام التوصيل المقدرة</label><input type="number" name="estimated_days" min="1" value="3" class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="flex-1 py-3 rounded-xl text-sm font-extrabold" style="color:var(--text-primary);background:linear-gradient(135deg,#4f8cff,#a78bfa)">إضافة الشركة</button>
                        <button type="button" @click="showCompanyModal = false" class="px-6 py-3 rounded-xl text-sm font-bold" style="background:#f1f3f8;color:#374151">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
