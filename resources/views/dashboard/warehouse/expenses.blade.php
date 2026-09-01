@extends('layouts.app')
@section('title', 'المصاريف')
@section('content')
<div class="space-y-6" dir="rtl" x-data="{ showModal: false }">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.warehouse.index') }}" class="p-2 rounded-lg transition-colors" style="color:var(--text-secondary)" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg></a>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">المصاريف</h1>
        </div>
        <div class="flex items-center gap-2">
            @foreach(['all'=>'الكل','today'=>'اليوم','7days'=>'7 أيام','30days'=>'30 يوم'] as $key => $label)
                <a href="?period={{ $key }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all" style="{{ request('period','all') === $key ? 'background:linear-gradient(135deg,#4f8cff,#a78bfa);color:var(--text-primary)' : 'background:rgba(255,255,255,0.04);color:#8a92a6' }}">{{ $label }}</a>
            @endforeach
            <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#f87171,#f472b6);color:var(--text-primary)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                مصروف جديد
            </button>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="surface-card rounded-2xl border p-4" style="border-color:var(--border)">
            <span class="text-xs font-medium" style="color:var(--text-secondary)">إجمالي المصاريف</span>
            <div class="text-xl font-extrabold mt-1" style="color:#f87171">{{ number_format($totalExpenses) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
        </div>
        @foreach($byType as $bt)
            <div class="surface-card rounded-2xl border p-4" style="border-color:var(--border)">
                <span class="text-xs font-medium" style="color:var(--text-secondary)">{{ \App\Models\Expense::TYPES[$bt->type] ?? $bt->type }}</span>
                <div class="text-xl font-extrabold mt-1" style="color:var(--text-primary)">{{ number_format($bt->total) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="surface-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:var(--table-header-bg)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">التاريخ</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">النوع</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المنتج</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المبلغ</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الملاحظات</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($expenses as $expense)
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-5 py-3 text-sm" style="color:var(--text-primary)">{{ $expense->date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(248,113,113,0.12);color:#f87171">{{ $expense->type_label }}</span></td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-primary)">{{ $expense->product->name ?? 'مصروف عام' }}</td>
                            <td class="px-5 py-3 font-bold" style="color:#f87171">{{ number_format($expense->amount) }} DA</td>
                            <td class="px-5 py-3 text-sm" style="color:var(--text-secondary)">{{ $expense->description ?: '—' }}</td>
                            <td class="px-5 py-3">
                                <form action="{{ route('dashboard.warehouse.deleteExpense', $expense->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المصروف؟')">@csrf @method('DELETE')
                                    <button type="submit" class="transition-colors" style="color:#f87171"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد مصاريف بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())<div class="px-4 py-3 border-t" style="border-color:var(--border)">{{ $expenses->links() }}</div>@endif
    </div>

    {{-- Add Expense Modal --}}
    <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div @click.away="showModal = false" x-show="showModal" x-transition class="modal-light rounded-2xl shadow-2xl w-full max-w-lg" x-cloak>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-extrabold" style="color:#1a1a2e">تسجيل مصروف جديد</h3>
                    <button @click="showModal = false" class="p-1 rounded-lg" style="color:#9ca3af"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form action="{{ route('dashboard.warehouse.storeExpense') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">نوع المصروف *</label>
                        <select name="type" required class="w-full rounded-xl px-4 py-3 text-sm font-medium">
                            <option value="">اختر النوع...</option>
                            @foreach(\App\Models\Expense::TYPES as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">المبلغ (د.ج) *</label><input type="number" name="amount" min="0" required class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                        <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">التاريخ *</label><input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl px-4 py-3 text-sm font-medium"></div>
                    </div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">مرتبط بمنتج</label>
                        <select name="product_id" class="w-full rounded-xl px-4 py-3 text-sm font-medium">
                            <option value="">بدون منتج (مصروف عام)</option>
                            @foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">الملاحظات</label><textarea name="description" rows="2" class="w-full rounded-xl px-4 py-3 text-sm font-medium" placeholder="مثال: شحن طردين للبليدة"></textarea></div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="flex-1 py-3 rounded-xl text-sm font-extrabold" style="background:linear-gradient(135deg,#f87171,#f472b6);color:var(--text-primary)">تسجيل المصروف</button>
                        <button type="button" @click="showModal = false" class="px-6 py-3 rounded-xl text-sm font-bold" style="background:#f1f3f8;color:#374151">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
