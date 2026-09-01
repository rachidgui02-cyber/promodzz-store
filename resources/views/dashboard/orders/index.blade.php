@extends('layouts.app')

@section('title', 'الطلبات')

@push('styles')
<style>
    .status-option {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.75rem 1rem; border-radius: 0.75rem; cursor: pointer;
        transition: all 0.15s; border: 1.5px solid var(--border);
    }
    .status-option:hover { background: var(--surface-raised); border-color: var(--muted); }
    .status-option:active { transform: scale(0.98); }

    .badge-pill {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: 0.7rem;
        font-weight: 600; cursor: pointer; transition: all 0.15s;
        border: 1.5px solid transparent; white-space: nowrap;
    }
    .badge-pill:hover { transform: translateY(-1px); }

    .sync-drawer {
        position: fixed; top: 0; left: 0; height: 100vh; width: 400px; max-width: 90vw;
        background: var(--surface-card); border-right: 1px solid var(--border); z-index: 1000;
        transform: translateX(-100%); transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
        display: flex; flex-direction: column;
    }
    .sync-drawer.open { transform: translateX(0); }
    .sync-drawer-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 999; display: none; }
    .sync-drawer-backdrop.show { display: block; }
    .sync-item { animation: syncItemIn 0.3s ease; }
    @keyframes syncItemIn { from { opacity:0; transform:translateX(-10px); } to { opacity:1; transform:translateX(0); } }
    .sync-spinner { width: 20px; height: 20px; border: 2px solid var(--border); border-top-color: #4f8cff; border-radius: 50%; animation: spin 0.7s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .order-row {
        display: grid;
        grid-template-columns: 40px 36px 44px 1fr 140px 130px 120px 110px 100px 56px 70px 60px;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        border-bottom: 1px solid var(--border);
        transition: all 0.15s;
        min-height: 60px;
    }
    .order-row:hover { background: var(--surface-raised); }
    .order-row.highlight { animation: rowFlash 0.6s ease; }
    @keyframes rowFlash { 0%,100%{background:transparent} 50%{background:rgba(79,140,255,0.08)} }

    .order-header {
        display: grid;
        grid-template-columns: 40px 36px 44px 1fr 140px 130px 120px 110px 100px 56px 70px 60px;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-bottom: 2px solid var(--border);
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--muted);
    }

    .source-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 8px;
        font-size: 10px; font-weight: 800; letter-spacing: -0.5px;
    }

    .delivery-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 8px; font-size: 14px;
    }

    .row-select {
        background: transparent; border: 1px solid transparent; color: var(--text-primary);
        font-size: 0.7rem; font-weight: 600; padding: 2px 4px; border-radius: 6px;
        transition: all 0.15s; cursor: pointer; width: 100%;
    }
    .row-select:hover { border-color: var(--muted); background: var(--surface-raised); }
    .row-select:focus { outline: none; border-color: #4f8cff; }

    .quick-check {
        width: 30px; height: 30px; border-radius: 50%; border: 2px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s; font-size: 14px;
    }
    .quick-check:hover { border-color: #34d399; background: rgba(52,211,153,0.1); }
    .quick-check.done { border-color: #34d399; background: #34d399; color: #fff; }

    @media (max-width: 1200px) {
        .order-row, .order-header {
            grid-template-columns: 36px 32px 40px 1fr 120px 100px 90px 80px 60px;
        }
        .order-row .hide-md, .order-header .hide-md { display: none; }
    }
    @media (max-width: 768px) {
        .order-row, .order-header {
            grid-template-columns: 36px 1fr 90px 60px;
        }
        .order-row .hide-sm, .order-header .hide-sm { display: none; }
    }
</style>
@endpush

@section('content')
<div class="space-y-3" dir="rtl">

    {{-- ═══════════════ ACTION BUTTONS ═══════════════ --}}
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('dashboard.orders.import') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all" style="background:#34d399">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة طلب
        </a>
        <a href="{{ route('dashboard.orders.import') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all" style="background:#a78bfa">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            رفع الطلبات
        </a>
        <button onclick="openSyncDrawer()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all" style="background:#34d399">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            مزامنة الطلبات
            @if(($pendingSyncCount ?? 0) > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold" style="background:rgba(255,255,255,0.25)">{{ $pendingSyncCount }}</span>
            @endif
        </button>

        <div class="mr-auto flex items-center gap-2">
            <span class="text-sm font-bold" style="color:var(--text-primary)">الكل</span>
            <span class="text-lg font-black" style="color:#4f8cff">{{ $totalOrders }}</span>
        </div>
    </div>

    {{-- ═══════════════ FILTER BAR ═══════════════ --}}
    <div class="rounded-xl p-3" style="background:var(--surface-card);border:1px solid var(--border)">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-3">
            <div class="flex-1 relative w-full lg:w-auto">
                <input type="text" id="searchInput" value="{{ $search ?? '' }}" placeholder="الاسم أو الهاتف أو رقم الطلب..."
                    class="w-full rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                    style="background:var(--surface-raised);border:1px solid var(--border);color:var(--text-primary)"
                    onkeydown="if(event.key==='Enter')navigate({search:this.value})">
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select onchange="navigate({wilaya:this.value||null})" class="row-select px-3 py-2 text-xs" style="background:var(--surface-raised);border:1px solid var(--border);width:140px">
                <option value="">كل الولايات</option>
                @foreach($wilayasList as $code => $name)
                    <option value="{{ $name }}" {{ ($wilayaFilter ?? '') === $name ? 'selected' : '' }}>{{ $code }} - {{ $name }}</option>
                @endforeach
            </select>

            @php
                $workflowFilters = [
                    ['key'=>null,'label'=>'الكل الحالات'],
                    ['key'=>'new','label'=>'طلب جديد'],
                    ['key'=>'to_call','label'=>'يحتاج اتصال'],
                    ['key'=>'no_answer','label'=>'لم يرد'],
                    ['key'=>'confirmed','label'=>'مؤكد'],
                    ['key'=>'in_transit','label'=>'في الطريق'],
                    ['key'=>'delivered','label'=>'تم التوصيل'],
                    ['key'=>'returned','label'=>'مرتجع'],
                    ['key'=>'cancelled','label'=>'ملغي'],
                ];
            @endphp
            <select onchange="navigate({workflow:this.value||null})" class="row-select px-3 py-2 text-xs" style="background:var(--surface-raised);border:1px solid var(--border);width:130px">
                @foreach($workflowFilters as $wf)
                    <option value="{{ $wf['key'] }}" {{ ($workflow ?? null) === $wf['key'] ? 'selected' : '' }}>{{ $wf['label'] }}</option>
                @endforeach
            </select>

            <select onchange="navigate({source:this.value||null})" class="row-select px-3 py-2 text-xs" style="background:var(--surface-raised);border:1px solid var(--border);width:120px">
                <option value="">كل المصادر</option>
                @foreach(\App\Models\Order::SOURCE_TYPES as $key => $label)
                    <option value="{{ $key }}" {{ ($sourceFilter ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            @php
                $timeFilters = [
                    ['key'=>null,'label'=>'كل الأوقات'],
                    ['key'=>'today','label'=>'اليوم'],
                    ['key'=>'yesterday','label'=>'أمس'],
                    ['key'=>'7days','label'=>'آخر 7 أيام'],
                    ['key'=>'30days','label'=>'آخر 30 يوم'],
                    ['key'=>'this_month','label'=>'هذا الشهر'],
                ];
            @endphp
            <select onchange="navigate({date:this.value||null})" class="row-select px-3 py-2 text-xs" style="background:var(--surface-raised);border:1px solid var(--border);width:120px">
                @foreach($timeFilters as $tf)
                    <option value="{{ $tf['key'] }}" {{ ($dateFilter ?? null) === $tf['key'] ? 'selected' : '' }}>{{ $tf['label'] }}</option>
                @endforeach
            </select>

            <button onclick="navigate({search:document.getElementById('searchInput').value})" class="px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors" style="background:#4f8cff">بحث</button>
        </div>
    </div>

    {{-- ═══════════════ STATUS BADGES ═══════════════ --}}
    <div class="flex flex-wrap gap-1.5">
        @php
            $badges = [
                ['key'=>null,'label'=>'الكل','count'=>$statusCounts['all'],'color'=>'#8a92a6','bg'=>'rgba(138,146,166,0.1)'],
                ['key'=>'new','label'=>'طلب جديد','count'=>$statusCounts['new'],'color'=>'#34d399','bg'=>'rgba(52,211,153,0.1)'],
                ['key'=>'to_call','label'=>'يحتاج اتصال','count'=>$statusCounts['to_call'],'color'=>'#fbbf24','bg'=>'rgba(251,191,36,0.1)'],
                ['key'=>'no_answer','label'=>'لم يرد','count'=>$statusCounts['no_answer'],'color'=>'#f87171','bg'=>'rgba(248,113,113,0.1)'],
                ['key'=>'confirmed','label'=>'مؤكد','count'=>$statusCounts['confirmed'],'color'=>'#4f8cff','bg'=>'rgba(79,140,255,0.1)'],
                ['key'=>'in_transit','label'=>'في الطريق','count'=>$statusCounts['in_transit'],'color'=>'#fb923c','bg'=>'rgba(251,146,60,0.1)'],
                ['key'=>'delivered','label'=>'تم التوصيل','count'=>$statusCounts['delivered'],'color'=>'#34d399','bg'=>'rgba(52,211,153,0.1)'],
                ['key'=>'returned','label'=>'مرتجع','count'=>$statusCounts['returned'],'color'=>'#f472b6','bg'=>'rgba(244,114,182,0.1)'],
                ['key'=>'cancelled','label'=>'ملغي','count'=>$statusCounts['cancelled'],'color'=>'#f87171','bg'=>'rgba(248,113,113,0.1)'],
            ];
        @endphp
        @foreach($badges as $badge)
            @php
                $isActive = ($badge['key'] === null && !$workflow && !$currentStatus) || $workflow === $badge['key'] || $currentStatus === $badge['key'];
            @endphp
            <a href="{{ route('dashboard.orders.index', array_filter(['workflow'=>$badge['key'],'date'=>$dateFilter,'search'=>$search,'source'=>$sourceFilter,'wilaya'=>$wilayaFilter])) }}"
               class="badge-pill" style="{{ $isActive ? "background:{$badge['color']};color:#fff;border-color:{$badge['color']}" : "background:{$badge['bg']};color:{$badge['color']};border-color:transparent" }}">
                {{ $badge['label'] }}
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold" style="{{ $isActive ? 'background:rgba(255,255,255,0.2)' : 'background:rgba(0,0,0,0.15)' }}">{{ $badge['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- ═══════════════ ORDERS TABLE ═══════════════ --}}
    <div class="rounded-xl overflow-hidden" style="background:var(--surface-card);border:1px solid var(--border)">

        {{-- Header --}}
        <div class="order-header">
            <div>✓</div>
            <div></div>
            <div></div>
            <div>المنتج / العميل</div>
            <div>الولاية</div>
            <div>البلدية</div>
            <div>الهاتف</div>
            <div>السعر</div>
            <div class="hide-md">الكمية</div>
            <div class="hide-md">المصدر</div>
            <div class="hide-sm">الوقت</div>
            <div>حالة</div>
        </div>

        {{-- Rows --}}
        <div id="ordersContainer">
            @forelse($orders ?? [] as $order)
                @php
                    $label = \App\Models\Order::getStatusLabel($order->status);
                    $hexColor = \App\Models\Order::getStatusHexColor($order->status);
                    $isFinal = in_array($order->status, ['delivered','returned','cancelled']);
                    $firstItem = $order->items->first();
                    $productImage = $firstItem && $firstItem->product && $firstItem->product->image ? asset('storage/'.$firstItem->product->image) : null;
                    $productName = $firstItem ? $firstItem->product_name : 'منتج';
                    $quantity = $firstItem ? $firstItem->quantity : 0;
                    $deliveryType = str_contains($order->notes ?? '', 'المكتب') ? 'stop_desk' : 'home';
                    $sourceKey = $order->source ?? 'other';
                    $sourceColor = match($sourceKey) {
                        'facebook' => '#1877F2',
                        'instagram' => '#E4405F',
                        'tiktok' => '#010101',
                        'direct' => '#34d399',
                        default => '#6b7280',
                    };
                @endphp
                <div id="order-card-{{ $order->id }}" class="order-row">

                    {{-- Quick Check --}}
                    <div>
                        <div class="quick-check {{ $order->status === 'delivered' ? 'done' : '' }}"
                             onclick="quickToggleStatus({{ $order->id }}, '{{ $order->status }}')"
                             title="{{ $label }}">
                            @if($order->status === 'delivered')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @endif
                        </div>
                    </div>

                    {{-- Edit --}}
                    <div>
                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="p-1.5 rounded-lg transition-colors" style="color:var(--muted)" onmouseover="this.style.color='#4f8cff'" onmouseout="this.style.color='var(--muted)'" title="تعديل">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>

                    {{-- Product Image --}}
                    <div>
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0" style="background:var(--surface-raised)">
                            @if($productImage)
                                <img src="{{ $productImage }}" class="w-full h-full object-cover" alt="" loading="lazy">
                            @else
                                <svg class="w-5 h-5" style="color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            @endif
                        </div>
                    </div>

                    {{-- Product + Customer --}}
                    <div class="min-w-0">
                        <p class="text-xs font-bold truncate" style="color:var(--text-primary)">{{ $productName }}</p>
                        <p class="text-xs font-semibold truncate mt-0.5" style="color:var(--text-primary)">{{ $order->customer_name }}</p>
                    </div>

                    {{-- Wilaya + Delivery Type --}}
                    <div class="flex items-center gap-1.5">
                        <div class="delivery-icon" style="background:{{ $deliveryType === 'home' ? 'rgba(139,92,246,0.15)' : 'rgba(251,191,36,0.15)' }}">
                            {{ $deliveryType === 'home' ? '🏠' : '🏢' }}
                        </div>
                        <select class="row-select" style="width:100px" onchange="quickUpdateField({{ $order->id }}, 'wilaya', this.value)">
                            <option value="">—</option>
                            @foreach($wilayasList as $code => $wname)
                                <option value="{{ $wname }}" {{ $order->wilaya === $wname ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Commune --}}
                    <div>
                        <span class="text-xs font-semibold" style="color:var(--text-primary)">{{ $order->commune ?: '—' }}</span>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <a href="tel:{{ $order->customer_phone }}" class="text-xs font-mono font-bold" style="color:#34d399" dir="ltr">{{ $order->customer_phone }}</a>
                    </div>

                    {{-- Price --}}
                    <div>
                        <p class="text-xs font-bold" style="color:var(--text-primary)">{{ number_format($order->total, 0) }} <span class="text-[10px] font-normal" style="color:var(--muted)">د.ج</span></p>
                        <p class="text-[10px] mt-0.5" style="color:var(--muted)">{{ number_format($order->shipping_cost, 0) }} + {{ number_format($order->subtotal, 0) }}</p>
                    </div>

                    {{-- Quantity --}}
                    <div class="hide-md">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" style="color:var(--muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            <span class="text-xs font-bold" style="color:var(--text-primary)">{{ $quantity }}</span>
                        </div>
                    </div>

                    {{-- Source --}}
                    <div class="hide-md">
                        @if($sourceKey && $sourceKey !== 'other')
                            <div class="source-badge" style="background:{{ $sourceColor }}15;color:{{ $sourceColor }}">
                                @if($sourceKey === 'facebook')
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @elseif($sourceKey === 'instagram')
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @elseif($sourceKey === 'tiktok')
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                @else
                                    <span class="text-[10px] font-bold">{{ strtoupper(substr($sourceKey, 0, 2)) }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Time --}}
                    <div class="hide-sm">
                        <p class="text-[11px] font-bold" style="color:var(--text-primary)">{{ $order->created_at->format('H:i') }}</p>
                        <p class="text-[10px]" style="color:var(--muted)">{{ $order->created_at->format('d/m/y') }}</p>
                    </div>

                    {{-- Status --}}
                    <div class="flex-shrink-0">
                        @if($isFinal)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold" style="background:{{ $hexColor }}15;color:{{ $hexColor }}">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $hexColor }}"></span>
                                {{ Str::limit($label, 10) }}
                            </span>
                        @else
                            <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')"
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold transition-all cursor-pointer"
                                style="background:var(--surface-raised);border:1px solid var(--border);color:var(--text-primary)"
                                onmouseover="this.style.borderColor='#4f8cff'" onmouseout="this.style.borderColor='var(--border)'"
                                id="status-btn-{{ $order->id }}">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $hexColor }}"></span>
                                <span id="status-text-{{ $order->id }}">{{ Str::limit($label, 10) }}</span>
                                <svg class="w-2.5 h-2.5" style="color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <svg class="w-14 h-14 mx-auto mb-3" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p style="color:var(--muted)" class="text-sm font-bold">لا توجد طلبات</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($orders ?? false)
        <div class="flex justify-center">{{ $orders->withQueryString()->links() }}</div>
    @endif
</div>

{{-- ═══════════════ STATUS MODAL ═══════════════ --}}
<div id="statusModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);z-index:999;display:none;align-items:center;justify-content:center" onclick="if(event.target===this)closeStatusModal()">
    <div style="background:var(--surface-card);border:1px solid var(--border);border-radius:1.25rem;width:90%;max-width:400px;max-height:85vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.3);color:var(--text-primary)" dir="rtl">
        <div class="flex items-center justify-between p-5" style="border-bottom:1px solid var(--border)">
            <div>
                <h3 class="text-lg font-bold" style="color:var(--text-primary)">تغيير الحالة</h3>
                <p class="text-xs mt-0.5" style="color:var(--muted)">الطلب: <span id="modalOrderNumber" class="font-mono" style="color:#4f8cff"></span></p>
            </div>
            <button onclick="closeStatusModal()" class="p-2 rounded-xl transition-colors" style="color:var(--muted)" onmouseover="this.style.background='var(--surface-raised)'" onmouseout="this.style.background='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalStatusOptions" class="p-3 space-y-1.5"></div>
    </div>
</div>

{{-- ═══════════════ SYNC DRAWER ═══════════════ --}}
<div id="syncDrawerBackdrop" class="sync-drawer-backdrop" onclick="closeSyncDrawer()"></div>
<div id="syncDrawer" class="sync-drawer" dir="rtl">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid var(--border)">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(52,211,153,0.1)">
                <svg class="w-5 h-5" style="color:#34d399" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-sm" style="color:var(--text-primary)">مزامنة الطلبات</h3>
                <p id="syncDrawerSubtitle" class="text-xs" style="color:var(--muted)">جارٍ التحقق من التحديثات...</p>
            </div>
        </div>
        <button onclick="closeSyncDrawer()" class="p-2 rounded-xl transition-colors" style="color:var(--muted)" onmouseover="this.style.background='var(--surface-raised)'" onmouseout="this.style.background='transparent'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div id="syncSummary" class="px-5 py-3 flex-shrink-0" style="border-bottom:1px solid var(--border);display:none">
        <div class="grid grid-cols-2 gap-2 text-center">
            <div class="rounded-xl py-3" style="background:var(--surface-raised)">
                <p id="syncUpdatedCount" class="text-lg font-bold" style="color:#34d399">0</p>
                <p class="text-[10px]" style="color:var(--muted)">تم التحديث</p>
            </div>
            <div class="rounded-xl py-3" style="background:var(--surface-raised)">
                <p id="syncUnchangedCount" class="text-lg font-bold" style="color:var(--text-primary)">0</p>
                <p class="text-[10px]" style="color:var(--muted)">بدون تغيير</p>
            </div>
        </div>
    </div>
    <div id="syncLoading" class="flex-1 flex items-center justify-center">
        <div class="text-center">
            <div class="sync-spinner mx-auto mb-3"></div>
            <p class="text-sm" style="color:var(--muted)">جاري مزامنة الطلبات...</p>
        </div>
    </div>
    <div id="syncResults" class="flex-1 overflow-y-auto p-3 space-y-1.5" style="display:none"></div>
    <div id="syncFooter" class="px-5 py-3 flex-shrink-0" style="border-top:1px solid var(--border);display:none">
        <button onclick="closeSyncDrawer();location.reload()" class="w-full py-2.5 rounded-xl text-sm font-bold text-white transition-colors" style="background:#34d399">تحديث الصفحة</button>
    </div>
</div>

<script>
var currentOrderId = null;

var STATUS_OPTIONS = {
    'new': [
        { status:'confirmed', label:'تأكيد الطلب', dot:'#4f8cff' },
        { status:'waiting_callback', label:'في انتظار عودة الاتصال', dot:'#fbbf24' },
        { status:'no_answer_1', label:'العميل لم يرد (1)', dot:'#f87171' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'waiting_callback': [
        { status:'confirmed', label:'تأكيد الطلب', dot:'#4f8cff' },
        { status:'no_answer_1', label:'العميل لم يرد (1)', dot:'#f87171' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'no_answer_1': [
        { status:'confirmed', label:'تأكيد الطلب', dot:'#4f8cff' },
        { status:'no_answer_2', label:'العميل لم يرد (2)', dot:'#f87171' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'no_answer_2': [
        { status:'confirmed', label:'تأكيد الطلب', dot:'#4f8cff' },
        { status:'no_answer_3', label:'العميل لم يرد (3)', dot:'#f87171' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'no_answer_3': [
        { status:'confirmed', label:'تأكيد الطلب', dot:'#4f8cff' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'customer_unavailable': [
        { status:'confirmed', label:'تأكيد الطلب', dot:'#4f8cff' },
        { status:'no_answer_1', label:'العميل لم يرد (1)', dot:'#f87171' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'confirmed': [
        { status:'processing', label:'قيد التجهيز', dot:'#a78bfa' },
        { status:'out_for_delivery', label:'في الطريق', dot:'#fb923c' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'processing': [
        { status:'shipped', label:'تم الشحن', dot:'#4f8cff' },
        { status:'out_for_delivery', label:'في الطريق', dot:'#fb923c' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'shipped': [
        { status:'out_for_delivery', label:'في الطريق', dot:'#fb923c' },
        { status:'cancelled', label:'إلغاء الطلب', dot:'#f87171' },
    ],
    'out_for_delivery': [
        { status:'delivered', label:'تم التوصيل', dot:'#34d399' },
        { status:'returned', label:'مرتجع', dot:'#f472b6' },
    ],
};

var STATUS_HEX = {
    'new':'#34d399','confirmed':'#22c55e','waiting_callback':'#fbbf24',
    'customer_unavailable':'#f87171','no_answer_1':'#f87171','no_answer_2':'#f87171','no_answer_3':'#f87171',
    'processing':'#a78bfa','shipped':'#4f8cff','out_for_delivery':'#fb923c',
    'delivered':'#34d399','returned':'#f472b6','cancelled':'#ef4444',
};

var LABEL_MAP = {
    'new':'طلب جديد','confirmed':'مؤكد','waiting_callback':'في انتظار عودة الاتصال',
    'customer_unavailable':'العميل غير متاح','no_answer_1':'لم يرد (1)',
    'no_answer_2':'لم يرد (2)','no_answer_3':'لم يرد (3)',
    'processing':'قيد التجهيز','shipped':'تم الشحن','out_for_delivery':'في الطريق',
    'delivered':'تم التوصيل','returned':'مرتجع','cancelled':'ملغي',
};

function openStatusModal(orderId, currentStatus) {
    currentOrderId = orderId;
    document.getElementById('modalOrderNumber').textContent = '#ORD-' + orderId;
    var options = STATUS_OPTIONS[currentStatus] || [];
    var container = document.getElementById('modalStatusOptions');
    container.innerHTML = '';
    options.forEach(function(opt) {
        container.insertAdjacentHTML('beforeend',
            '<button onclick="setStatus(' + orderId + ',\'' + opt.status + '\')" class="status-option">' +
            '<span class="w-3 h-3 rounded-full flex-shrink-0" style="background:' + opt.dot + '"></span>' +
            '<span class="flex-1 text-right text-sm font-medium" style="color:var(--text-primary)">' + opt.label + '</span>' +
            '<svg class="w-4 h-4 flex-shrink-0" style="color:var(--muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>' +
            '</button>'
        );
    });
    document.getElementById('statusModal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
    currentOrderId = null;
}

function setStatus(orderId, newStatus) {
    closeStatusModal();
    var btn = document.getElementById('status-btn-' + orderId);
    if (btn) btn.style.opacity = '0.5';
    fetch('/orders/' + orderId + '/status', {
        method: 'PATCH',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'Accept':'application/json' },
        body: JSON.stringify({ status: newStatus })
    })
    .then(function(r){return r.json();})
    .then(function(data){
        if(data.success){ updateRowUI(orderId,newStatus); updateBadgeCounts(); }
        else{ alert(data.message||'حدث خطأ'); if(btn) btn.style.opacity='1'; }
    })
    .catch(function(){ location.reload(); });
}

function updateRowUI(orderId, newStatus) {
    var card = document.getElementById('order-card-' + orderId);
    if(!card) return;
    var dot = STATUS_HEX[newStatus]||'#f87171';
    var label = LABEL_MAP[newStatus]||newStatus;
    var isFinal = ['delivered','returned','cancelled'].indexOf(newStatus)!==-1;
    var statusDiv = card.querySelector('.flex-shrink-0:last-child');
    if(isFinal){
        statusDiv.innerHTML='<span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold" style="background:'+dot+'15;color:'+dot+'"><span class="w-1.5 h-1.5 rounded-full" style="background:'+dot+'"></span>'+label+'</span>';
        var check = card.querySelector('.quick-check');
        if(newStatus==='delivered'){
            check.classList.add('done');
            check.innerHTML='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/></svg>';
        }
    } else {
        statusDiv.innerHTML='<button onclick="openStatusModal('+orderId+',\''+newStatus+'\')" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold transition-all cursor-pointer" id="status-btn-'+orderId+'" style="background:var(--surface-raised);border:1px solid var(--border);color:var(--text-primary)"><span class="w-1.5 h-1.5 rounded-full" style="background:'+dot+'"></span><span id="status-text-'+orderId+'">'+label+'</span><svg class="w-2.5 h-2.5" style="color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>';
    }
    card.classList.add('highlight');
    setTimeout(function(){card.classList.remove('highlight');},700);
}

function quickToggleStatus(orderId, currentStatus) {
    if(currentStatus === 'new') {
        setStatus(orderId, 'confirmed');
    } else if(currentStatus === 'confirmed') {
        setStatus(orderId, 'processing');
    }
}

function quickUpdateField(orderId, field, value) {
    fetch('/orders/' + orderId + '/quick-update', {
        method: 'PATCH',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'Accept':'application/json' },
        body: JSON.stringify({ [field]: value })
    })
    .then(function(r){return r.json();})
    .then(function(data){
        if(!data.success) alert(data.message||'حدث خطأ');
    })
    .catch(function(){});
}

function updateBadgeCounts() {
    var params = new URLSearchParams(window.location.search);
    fetch('/orders?'+params.toString(), {headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){return r.text();})
    .then(function(html){
        var doc = new DOMParser().parseFromString(html,'text/html');
        doc.querySelectorAll('.badge-pill').forEach(function(nb,i){
            var ob = document.querySelectorAll('.badge-pill')[i];
            if(ob){ var nc=nb.querySelector('.badge-pill span:last-child'); var oc=ob.querySelector('.badge-pill span:last-child'); if(nc&&oc)oc.textContent=nc.textContent; }
        });
    }).catch(function(){});
}

function navigate(params){
    var c=new URLSearchParams(window.location.search);
    for(var k in params){ if(params[k]===null||params[k]===''||params[k]===undefined)c.delete(k); else c.set(k,params[k]); }
    c.delete('page');
    window.location='{{route("dashboard.orders.index")}}?'+c.toString();
}

document.addEventListener('keydown',function(e){ if(e.key==='Escape'){closeStatusModal();closeSyncDrawer();} });

function openSyncDrawer(){
    document.getElementById('syncDrawerBackdrop').classList.add('show');
    document.getElementById('syncDrawer').classList.add('open');
    document.getElementById('syncLoading').style.display='flex';
    document.getElementById('syncResults').style.display='none';
    document.getElementById('syncSummary').style.display='none';
    document.getElementById('syncFooter').style.display='none';
    document.getElementById('syncDrawerSubtitle').textContent='جارٍ التحقق من التحديثات...';
    startSync();
}
function closeSyncDrawer(){
    document.getElementById('syncDrawerBackdrop').classList.remove('show');
    document.getElementById('syncDrawer').classList.remove('open');
}
function startSync(){
    fetch('/orders/sync',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'Accept':'application/json'},body:JSON.stringify({})})
    .then(function(r){return r.json();})
    .then(function(data){
        document.getElementById('syncLoading').style.display='none';
        document.getElementById('syncResults').style.display='block';
        document.getElementById('syncSummary').style.display='block';
        document.getElementById('syncFooter').style.display='block';
        document.getElementById('syncUpdatedCount').textContent=data.updated||0;
        document.getElementById('syncUnchangedCount').textContent=data.unchanged||0;
        document.getElementById('syncDrawerSubtitle').textContent=data.message||'تمت المزامنة';
        var c=document.getElementById('syncResults'); c.innerHTML='';
        if(data.details&&data.details.length>0){
            data.details.forEach(function(item,idx){
                var dot=STATUS_HEX[item.to]||'#f87171';
                var label=item.label||LABEL_MAP[item.to]||item.to;
                c.insertAdjacentHTML('beforeend','<div class="sync-item flex items-center gap-3 rounded-xl px-4 py-3" style="background:var(--surface-raised);border:1px solid var(--border);animation-delay:'+idx*0.05+'s"><span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm" style="background:'+dot+'15;color:'+dot+'">📋</span><div class="flex-1 min-w-0"><p class="text-xs font-mono" style="color:var(--text-primary)">#'+item.order+'</p><p class="text-[10px]" style="color:var(--muted)">من: '+(LABEL_MAP[item.from]||item.from)+' → '+label+'</p></div><span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:'+dot+'15;color:'+dot+'">'+label+'</span></div>');
            });
        } else {
            c.innerHTML='<div class="text-center py-10"><p style="color:var(--muted)" class="text-sm">جميع الطلبات محدثة بالفعل</p></div>';
        }
        updateBadgeCounts();
    })
    .catch(function(){
        document.getElementById('syncLoading').style.display='none';
        document.getElementById('syncResults').style.display='block';
        document.getElementById('syncFooter').style.display='block';
        document.getElementById('syncDrawerSubtitle').textContent='حدث خطأ';
        document.getElementById('syncResults').innerHTML='<div class="text-center py-10"><p style="color:#f87171" class="text-sm">تعذر الاتصال</p></div>';
    });
}
</script>
@endsection
