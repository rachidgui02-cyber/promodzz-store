@extends('layouts.app')
@section('title', 'منتجاتك')
@section('content')
<div class="space-y-6" dir="rtl">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-white">منتجاتك</h1>
        <a href="{{ route('dashboard.products.create') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#4f8cff,#a78bfa)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة منتج
        </a>
    </div>
    <div class="surface-card rounded-2xl border p-5" style="border-color:#232530">
        <form action="{{ route('dashboard.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search', '') }}" placeholder="بحث بالاسم، SKU..." class="w-full rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-accent-blue pr-10" style="background:#1a1c25;border:1px solid #232530;color:#fff" placeholder="بحث...">
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2" style="color:#555a6e" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <select name="category_id" class="rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-accent-blue" style="background:#1a1c25;border:1px solid #232530;color:#fff">
                <option value="">جميع الفئات</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:scale-[1.02]" style="background:linear-gradient(135deg,#4f8cff,#a78bfa)">بحث</button>
        </form>
    </div>
    <div class="surface-card rounded-2xl border overflow-hidden" style="border-color:#232530">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03)">
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">المنتج</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">SKU</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">م. الشراء</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">م. البيع</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">هامش الربح</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">المخزون</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">الحالة</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:#8a92a6">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#232530">
                    @forelse($products ?? [] as $product)
                        @php $margin = $product->buy_price > 0 ? (($product->sell_price - $product->buy_price) / $product->buy_price) * 100 : 0; @endphp
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.04);border:1px solid #232530">
                                        @if($product->image)<img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-10 h-10 rounded-xl object-cover">@else<svg class="w-5 h-5" style="color:#555a6e" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>@endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-white text-sm">{{ $product->name }}</p>
                                        @if($product->category)<p class="text-xs" style="color:#8a92a6">{{ $product->category->name }}</p>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs" style="color:#8a92a6">{{ $product->sku }}</td>
                            <td class="px-5 py-3" style="color:#8a92a6">{{ number_format($product->buy_price, 2) }} DA</td>
                            <td class="px-5 py-3 font-medium text-white">{{ number_format($product->sell_price, 2) }} DA</td>
                            <td class="px-5 py-3"><span style="color:#34d399" class="font-medium">+{{ number_format($margin, 1) }}%</span></td>
                            <td class="px-5 py-3">
                                @if($product->stock_quantity <= $product->low_stock_threshold)<span class="font-bold" style="color:#f87171">{{ $product->stock_quantity }}</span>@else<span class="text-white">{{ $product->stock_quantity }}</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                @if($product->is_active)<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(52,211,153,0.12);color:#34d399">نشط</span>@else<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(107,114,128,0.12);color:#6b7280">غير نشط</span>@endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 rounded-lg transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 top-full mt-1 w-52 rounded-xl shadow-2xl z-50 overflow-hidden py-1" style="background:#1a1c25;border:1px solid #232530">
                                        <a href="{{ route('storefront.product', [$product->shop->slug, $product->id]) }}" target="_blank" class="flex items-center gap-2 px-3 py-2 text-sm transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#8a92a6'"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg> صفحة المنتج</a>
                                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="flex items-center gap-2 px-3 py-2 text-sm transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#8a92a6'"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg> تعديل</a>
                                        <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm transition-colors" style="color:#8a92a6" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#f87171'" onmouseout="this.style.background='transparent';this.style.color='#8a92a6'"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg> حذف</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-10 text-center" style="color:#555a6e">لا توجد منتجات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products ?? false)<div class="p-5 border-t" style="border-color:#232530">{{ $products->withQueryString()->links() }}</div>@endif
    </div>
</div>
@endsection
