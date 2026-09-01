@extends('layouts.app')
@section('title', 'الكوبونات')
@section('content')
<div class="space-y-6" dir="rtl" x-data="{ showModal: false }">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-white">الكوبونات</h1>
        <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02]" style="background:#111827;color:#ffffff">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة كوبون
        </button>
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:rgba(255,255,255,0.03)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الكود</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">النوع</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">القيمة</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحد الأدنى</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحد الأقصى</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">مر×</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحالة</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($coupons ?? [] as $coupon)
                        <tr class="transition-colors" onmouseover="this.style.background=var(--hover-bg)" onmouseout="this.style.background='transparent'">
                            <td class="px-5 py-3"><span class="font-mono font-bold px-2.5 py-1 rounded-lg text-white" style="background:var(--input-bg)">{{ $coupon->code }}</span></td>
                            <td class="px-5 py-3">
                                @if($coupon->type === 'fixed')<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(79,140,255,0.12);color:#4f8cff">مبلغ ثابت</span>@else<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(167,139,250,0.12);color:#a78bfa">نسبة مئوية</span>@endif
                            </td>
                            <td class="px-5 py-3 font-bold text-white">@if($coupon->type === 'fixed'){{ number_format($coupon->value, 2) }} DA@else{{ $coupon->value }}%@endif</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ number_format($coupon->min_order_amount ?? 0, 2) }} DA</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $coupon->usage_limit ?? '∞' }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $coupon->used_count ?? 0 }}</td>
                            <td class="px-5 py-3">
                                @if($coupon->is_active)<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(52,211,153,0.12);color:#34d399">نشط</span>@else<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(107,114,128,0.12);color:#6b7280">غير نشط</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('dashboard.coupons.toggle', $coupon->id) }}" method="POST">@csrf @method('PATCH')
                                        <button type="submit" class="transition-colors" style="color:{{ $coupon->is_active ? '#fb923c' : '#34d399' }}" title="{{ $coupon->is_active ? 'تعطيل' : 'تفعيل' }}">
                                            @if($coupon->is_active)<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>@else<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>@endif
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">@csrf @method('DELETE')
                                        <button type="submit" class="transition-colors" style="color:#f87171" title="حذف"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد كوبونات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div @click.away="showModal = false" x-show="showModal" x-transition class="modal-light rounded-2xl shadow-2xl w-full max-w-md" x-cloak>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-extrabold" style="color:#1a1a2e">إضافة كوبون جديد</h3>
                    <button @click="showModal = false" class="p-1 rounded-lg" style="color:#9ca3af"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form action="{{ route('dashboard.coupons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">كود الكوبون *</label><input type="text" name="code" required class="w-full rounded-xl px-4 py-3 text-sm font-medium font-mono" dir="ltr" placeholder="خصم10">@error('code')<span class="text-sm mt-1 block" style="color:#f87171">{{ $message }}</span>@enderror</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">النوع *</label><select name="type" required class="w-full rounded-xl px-4 py-3 text-sm font-medium"><option value="fixed">مبلغ ثابت (DA)</option><option value="percent">النسبة المئوية (%)</option></select></div>
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">القيمة *</label><input type="number" name="value" step="0.01" min="0" required class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">الحد الأدنى (DA)</label><input type="number" name="min_order_amount" step="0.01" min="0" value="0" class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">الحد الأقصى للاستخدام</label><input type="number" name="usage_limit" min="1" class="w-full rounded-xl px-4 py-3 text-sm font-medium" placeholder="بدون حد"></div>
                    </div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">تاريخ الانتهاء</label><input type="datetime-local" name="expires_at" class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="flex-1 py-3 rounded-xl text-sm font-extrabold text-white" style="background:#111827;color:#ffffff">إضافة الكوبون</button>
                        <button type="button" @click="showModal = false" class="px-6 py-3 rounded-xl text-sm font-bold" style="background:#f1f3f8;color:#374151">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
