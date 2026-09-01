@extends('layouts.app')

@section('title', 'مركز الاتصال')

@section('content')
<div class="space-y-6" dir="rtl" x-data="callCenter()">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">مركز الاتصال</h1>
            <p class="text-sm mt-1" style="color:var(--text-secondary)">إدارة المكالمات وتأكيد الطلبات</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium" style="color:var(--text-secondary)">{{ now()->format('l, d F Y') }}</span>
        </div>
    </div>

    {{-- ═══════ Stats Cards ═══════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(79,140,255,0.1)">
                    <svg class="w-5 h-5" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">إجمالي المكالمات</span>
                    <div class="text-xl font-extrabold" style="color:#4f8cff">{{ $todayStats['total_calls'] }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(52,211,153,0.1)">
                    <svg class="w-5 h-5" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">مؤكدة</span>
                    <div class="text-xl font-extrabold" style="color:#34d399">{{ $todayStats['confirmed'] }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(248,113,113,0.1)">
                    <svg class="w-5 h-5" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">ملغية</span>
                    <div class="text-xl font-extrabold" style="color:#f87171">{{ $todayStats['cancelled'] }}</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(251,191,36,0.1)">
                    <svg class="w-5 h-5" style="color:#fbbf24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">بانتظار</span>
                    <div class="text-xl font-extrabold" style="color:#fbbf24">{{ $todayStats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════ Main Content ═══════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pending Queue --}}
        <div class="lg:col-span-1 stat-card overflow-hidden" style="max-height:600px;display:flex;flex-direction:column">
            <div class="px-5 py-4 border-b flex items-center justify-between flex-shrink-0" style="border-color:var(--divider)">
                <h2 class="text-sm font-bold" style="color:var(--text-primary)">قائمة الانتظار</h2>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:rgba(251,191,36,0.12);color:#fbbf24">{{ $pendingCalls->count() }}</span>
            </div>
            <div class="flex-1 overflow-y-auto p-3 space-y-2">
                @forelse($pendingCalls as $order)
                    @php
                        $statusColor = match($order->status) {
                            'new' => '#4f8cff',
                            'waiting_callback' => '#fbbf24',
                            'no_answer_1' => '#fb923c',
                            'no_answer_2' => '#f97316',
                            'customer_unavailable' => '#a78bfa',
                            default => '#6b7280',
                        };
                        $statusLabel = match($order->status) {
                            'new' => 'جديد',
                            'waiting_callback' => 'انتظار',
                            'no_answer_1' => 'لم يجب 1',
                            'no_answer_2' => 'لم يجب 2',
                            'customer_unavailable' => 'غير متاح',
                            default => $order->status,
                        };
                    @endphp
                    <div class="rounded-xl px-4 py-3 cursor-pointer transition-all"
                         style="background:var(--surface-raised);border:1px solid var(--border)"
                         onmouseover="this.style.borderColor='{{ $statusColor }}'"
                         onmouseout="this.style.borderColor='var(--border)'"
                         @click="selectOrder({{ json_encode(['id'=>$order->id,'order_number'=>$order->order_number,'customer_name'=>$order->customer_name,'customer_phone'=>$order->customer_phone,'total'=>$order->total,'wilaya'=>$order->wilaya,'status'=>$order->status,'call_attempts'=>$order->call_attempts,'notes'=>$order->notes,'created_at'=>$order->created_at->format('d/m/Y H:i'),'items'=>$order->items->map(fn($i)=>['name'=>$i->product_name,'qty'=>$i->quantity])->toArray()]) }})">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-mono font-bold text-xs" style="color:var(--text-primary)">#{{ $order->order_number }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:{{ $statusColor }}20;color:{{ $statusColor }}">{{ $statusLabel }}</span>
                        </div>
                        <p class="text-xs font-semibold" style="color:var(--text-primary)">{{ $order->customer_name }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[11px]" style="color:var(--text-secondary)" dir="ltr">{{ $order->customer_phone }}</span>
                            <span class="text-xs font-bold" style="color:var(--text-primary)">{{ number_format($order->total) }} <span class="text-[10px] font-normal" style="color:var(--text-tertiary)">DA</span></span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <svg class="w-12 h-12 mx-auto mb-3" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        <p class="text-sm font-bold" style="color:var(--text-tertiary)">لا توجد طلبات بانتظار</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Active Call Panel --}}
        <div class="lg:col-span-2 stat-card overflow-hidden" style="min-height:500px;display:flex;flex-direction:column">
            <div class="px-5 py-4 border-b flex items-center justify-between flex-shrink-0" style="border-color:var(--divider)">
                <h2 class="text-sm font-bold" style="color:var(--text-primary)">الطلب الحالي</h2>
                <button @click="nextOrder()" :disabled="loading"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white transition-all"
                    :style="loading ? 'background:#6b7280;cursor:not-allowed' : 'background:#4f8cff;cursor:pointer'"
                    onmouseover="if(!this.disabled)this.style.background='#3b82f6'"
                    onmouseout="if(!this.disabled)this.style.background='#4f8cff'">
                    <svg class="w-4 h-4" :class="{'animate-spin':loading}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    التالي
                </button>
            </div>

            <div class="flex-1 p-5">
                {{-- Empty State --}}
                <div x-show="!currentOrder" class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        <p class="text-sm font-bold mb-1" style="color:var(--text-tertiary)">اختر طلباً أو اضغط "التالي"</p>
                        <p class="text-xs" style="color:var(--text-tertiary)">ابدأ بالاتصال بالعميل接下来</p>
                    </div>
                </div>

                {{-- Order Details --}}
                <div x-show="currentOrder" x-cloak>
                    <div class="rounded-2xl overflow-hidden" style="background:var(--surface-raised);border:1px solid var(--border)">
                        {{-- Order Header --}}
                        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid var(--border)">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-mono font-extrabold text-sm" style="background:rgba(79,140,255,0.1);color:#4f8cff" x-text="'#' + (currentOrder?.order_number || '')"></div>
                                <div>
                                    <p class="text-sm font-bold" style="color:var(--text-primary)" x-text="currentOrder?.customer_name || ''"></p>
                                    <p class="text-xs" style="color:var(--text-secondary)" x-text="currentOrder?.created_at || ''"></p>
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="text-lg font-extrabold" style="color:var(--text-primary)" x-text="(currentOrder?.total ? Number(currentOrder.total).toLocaleString() : '0') + ' DA'"></p>
                                <p class="text-[11px]" style="color:var(--text-secondary)" x-text="currentOrder?.wilaya || ''"></p>
                            </div>
                        </div>

                        {{-- Customer Info --}}
                        <div class="px-5 py-4" style="border-bottom:1px solid var(--border)">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[11px] font-bold mb-1" style="color:var(--text-tertiary)">رقم الهاتف</p>
                                    <a :href="'tel:' + (currentOrder?.customer_phone || '')"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all"
                                       style="background:rgba(52,211,153,0.1);color:#34d399;border:1px solid rgba(52,211,153,0.2)"
                                       onmouseover="this.style.background='rgba(52,211,153,0.2)'"
                                       onmouseout="this.style.background='rgba(52,211,153,0.1)'">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                        <span x-text="currentOrder?.customer_phone || ''" dir="ltr"></span>
                                    </a>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold mb-1" style="color:var(--text-tertiary)">محاولات الاتصال</p>
                                    <div class="flex items-center gap-1">
                                        <template x-for="i in (currentOrder?.call_attempts || 0)" :key="i">
                                            <div class="w-3 h-3 rounded-full" style="background:#4f8cff"></div>
                                        </template>
                                        <template x-if="!(currentOrder?.call_attempts || 0)">
                                            <span class="text-xs" style="color:var(--text-tertiary)">لم يتم الاتصال بعد</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Products --}}
                        <div class="px-5 py-4" style="border-bottom:1px solid var(--border)">
                            <p class="text-[11px] font-bold mb-2" style="color:var(--text-tertiary)">المنتجات</p>
                            <div class="space-y-2">
                                <template x-for="(item, idx) in (currentOrder?.items || [])" :key="idx">
                                    <div class="flex items-center justify-between rounded-lg px-3 py-2" style="background:var(--surface-card)">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:var(--surface-raised)">
                                                <svg class="w-4 h-4" style="color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold" style="color:var(--text-primary)" x-text="item.name"></span>
                                        </div>
                                        <span class="text-xs font-bold" style="color:var(--text-secondary)" x-text="'x' + item.qty"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div x-show="currentOrder?.notes" class="px-5 py-4" style="border-bottom:1px solid var(--border)">
                            <p class="text-[11px] font-bold mb-1" style="color:var(--text-tertiary)">ملاحظات</p>
                            <p class="text-xs" style="color:var(--text-secondary)" x-text="currentOrder?.notes || ''"></p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="px-5 py-4">
                            <p class="text-[11px] font-bold mb-3" style="color:var(--text-tertiary)">إجراءات سريعة</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <button @click="updateStatus('confirmed')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all"
                                    style="background:#34d399"
                                    onmouseover="this.style.background='#2cc088'" onmouseout="this.style.background='#34d399'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    تأكيد
                                </button>

                                <button @click="updateStatus('cancelled')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all"
                                    style="background:#f87171"
                                    onmouseover="this.style.background='#ef4444'" onmouseout="this.style.background='#f87171'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    إلغاء
                                </button>

                                <button @click="updateStatus('no_answer_1')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                                    style="background:rgba(251,146,60,0.1);color:#fb923c;border:1px solid rgba(251,146,60,0.2)"
                                    onmouseover="this.style.background='rgba(251,146,60,0.2)'" onmouseout="this.style.background='rgba(251,146,60,0.1)'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                    لم يجب
                                </button>

                                <button @click="updateStatus('waiting_callback')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                                    style="background:rgba(251,191,36,0.1);color:#fbbf24;border:1px solid rgba(251,191,36,0.2)"
                                    onmouseover="this.style.background='rgba(251,191,36,0.2)'" onmouseout="this.style.background='rgba(251,191,36,0.1)'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    إعادة اتصال
                                </button>

                                <button @click="updateStatus('customer_unavailable')"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                                    style="background:rgba(167,139,250,0.1);color:#a78bfa;border:1px solid rgba(167,139,250,0.2)"
                                    onmouseover="this.style.background='rgba(167,139,250,0.2)'" onmouseout="this.style.background='rgba(167,139,250,0.1)'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                                    غير متاح
                                </button>

                                <button @click="nextOrder()"
                                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all"
                                    style="background:#4f8cff"
                                    onmouseover="this.style.background='#3b82f6'" onmouseout="this.style.background='#4f8cff'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062A1.125 1.125 0 013 16.81V8.688zM12.75 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062a1.125 1.125 0 01-1.683-.977V8.688z"/></svg>
                                    التالي
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 px-5 py-3 rounded-xl shadow-2xl text-sm font-bold z-50"
        :style="toastType === 'success' ? 'background:#34d399;color:#fff' : toastType === 'error' ? 'background:#f87171;color:#fff' : 'background:#4f8cff;color:#fff'"
        x-text="toast"></div>
</div>

<script>
function callCenter() {
    return {
        currentOrder: null,
        loading: false,
        toast: '',
        toastType: 'success',

        init() {
            this.loadStats();
        },

        selectOrder(order) {
            this.currentOrder = order;
        },

        nextOrder() {
            this.loading = true;
            fetch('{{ route("dashboard.callCenter.next") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                this.loading = false;
                if (data.success) {
                    this.currentOrder = data.order;
                    this.showToast('تم تحميل الطلب #' + data.order.order_number, 'success');
                } else {
                    this.showToast(data.message, 'info');
                }
            })
            .catch(() => {
                this.loading = false;
                this.showToast('حدث خطأ', 'error');
            });
        },

        updateStatus(status) {
            if (!this.currentOrder) return;
            var orderId = this.currentOrder.id;
            fetch('/orders/' + orderId + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.showToast('تم تحديث الحالة بنجاح', 'success');
                    this.currentOrder = null;
                    this.loadStats();
                } else {
                    this.showToast(data.message || 'حدث خطأ', 'error');
                }
            })
            .catch(() => this.showToast('حدث خطأ', 'error'));
        },

        loadStats() {
            fetch('{{ route("dashboard.callCenter.index") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var cards = doc.querySelectorAll('.stat-card .text-xl.font-extrabold');
                var myCards = document.querySelectorAll('.stat-card .text-xl.font-extrabold');
                cards.forEach(function(c, i) { if (myCards[i]) myCards[i].textContent = c.textContent; });
            }).catch(function(){});
        },

        showToast(msg, type) {
            this.toast = msg;
            this.toastType = type || 'success';
            setTimeout(() => { this.toast = ''; }, 3000);
        }
    }
}
</script>
@endsection
