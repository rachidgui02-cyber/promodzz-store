@extends('layouts.app')
@section('title', 'الفئات')
@section('content')
<div class="max-w-4xl mx-auto space-y-6" dir="rtl">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-white">الفئات</h1>
        <span class="text-sm font-medium" style="color:var(--text-secondary)">{{ ($categories ?? collect())->count() }} فئة</span>
    </div>
    <div class="stat-card rounded-2xl border p-5" style="border-color:var(--border)">
        <form action="{{ route('dashboard.categories.store') }}" method="POST" class="flex gap-3">
            @csrf
            <div class="flex-1">
                <input type="text" name="name" required class="w-full rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-accent-blue" style="background:var(--input-bg);border:1px solid var(--border);color:var(--text-primary)" placeholder="اسم الفئة الجديدة...">
                @error('name') <span class="text-sm mt-1 block" style="color:#f87171">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02]" style="background:#111827;color:#ffffff">
                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                إضافة
            </button>
        </form>
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03)">
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">#</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">اسم الفئة</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">عدد المنتجات</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">تاريخ الإنشاء</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($categories ?? [] as $category)
                        <tr class="transition-colors" x-data="{ editing: false }" onmouseover="this.style.background=var(--hover-bg)" onmouseout="this.style.background='transparent'">
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $category->id }}</td>
                            <td class="px-5 py-3">
                                <div x-show="!editing"><span class="font-medium text-white">{{ $category->name }}</span></div>
                                <div x-show="editing" x-cloak>
                                    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST" class="flex gap-2">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}" required class="rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-accent-blue" style="background:var(--input-bg);border:1px solid #4f8cff;color:var(--text-primary)">
                                        <button type="submit" class="transition-colors" style="color:#34d399"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                        <button type="button" @click="editing = false" class="transition-colors" style="color:#f87171"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:var(--input-bg);color:var(--text-secondary)">{{ $category->products_count ?? $category->products->count() ?? 0 }} منتج</span>
                            </td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)">{{ $category->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="editing = !editing" class="transition-colors" style="color:#4f8cff" title="تعديل"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                    <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="transition-colors" style="color:#f87171" title="حذف"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد فئات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
