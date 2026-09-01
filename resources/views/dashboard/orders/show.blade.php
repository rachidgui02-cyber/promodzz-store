@extends('layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="space-y-6" dir="rtl">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.orders.index') }}" class="w-10 h-10 rounded-xl bg-dark-800 border border-dark-700 flex items-center justify-center text-dark-200 hover:text-dark-100 hover:border-dark-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-dark-100">طلب #{{ $order->order_number }}</h1>
                <p class="text-dark-200 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @php
                $statusColors = [
                    'new' => 'bg-blue-500/20 text-blue-400',
                    'confirmed' => 'bg-indigo-500/20 text-indigo-400',
                    'preparing' => 'bg-violet-500/20 text-violet-400',
                    'shipped' => 'bg-amber-500/20 text-amber-400',
                    'in_transit' => 'bg-orange-500/20 text-orange-400',
                    'delivered' => 'bg-emerald-500/20 text-emerald-400',
                    'returned' => 'bg-rose-500/20 text-rose-400',
                    'cancelled' => 'bg-slate-500/20 text-slate-400',
                ];
                $statusLabels = [
                    'new' => 'جديد',
                    'confirmed' => 'مؤكد',
                    'preparing' => 'قيد التجهيز',
                    'shipped' => 'تم الشحن',
                    'in_transit' => 'في الطريق',
                    'delivered' => 'تم التوصيل',
                    'returned' => 'مسترجع',
                    'cancelled' => 'ملغي',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-dark-700 text-dark-200' }}">
                {{ $statusLabels[$order->status] ?? $order->status }}
            </span>
            <a href="{{ route('dashboard.orders.label', $order->id) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                طباعة الملصق
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
                <h2 class="text-lg font-bold text-dark-100 mb-4">العميل</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-dark-200 text-xs mb-1">الاسم</p>
                        <p class="text-dark-100 font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-dark-200 text-xs mb-1">الهاتف</p>
                        <p class="text-dark-100 font-medium" dir="ltr">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-dark-200 text-xs mb-1">العنوان</p>
                        <p class="text-dark-100 font-medium">{{ $order->customer_address }}</p>
                    </div>
                    <div>
                        <p class="text-dark-200 text-xs mb-1">الولاية</p>
                        <p class="text-dark-100 font-medium">{{ $order->wilaya }}</p>
                    </div>
                    @if($order->commune)
                        <div>
                            <p class="text-dark-200 text-xs mb-1">البلدية</p>
                            <p class="text-dark-100 font-medium">{{ $order->commune }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-dark-900 rounded-2xl border border-dark-800">
                <div class="p-5 border-b border-dark-800">
                    <h2 class="text-lg font-bold text-dark-100">المنتجات</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" dir="rtl">
                        <thead>
                            <tr class="border-b border-dark-800 text-dark-200">
                                <th class="text-right px-5 py-3 font-medium">المنتج</th>
                                <th class="text-right px-5 py-3 font-medium">السعر</th>
                                <th class="text-right px-5 py-3 font-medium">الكمية</th>
                                <th class="text-right px-5 py-3 font-medium">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b border-dark-800/50">
                                    <td class="px-5 py-3 text-dark-100">{{ $item->product_name }}</td>
                                    <td class="px-5 py-3 text-dark-200">{{ number_format($item->price, 2) }} DA</td>
                                    <td class="px-5 py-3 text-dark-200">{{ $item->quantity }}</td>
                                    <td class="px-5 py-3 text-dark-100 font-bold">{{ number_format($item->price * $item->quantity, 2) }} DA</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
                <h2 class="text-lg font-bold text-dark-100 mb-4">الملخص المالي</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-dark-200">المجموع الفرعي</span>
                        <span class="text-dark-100">{{ number_format($order->subtotal ?? $order->total - ($order->shipping_cost ?? 0), 2) }} DA</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-dark-200">التوصيل</span>
                        <span class="text-dark-100">{{ number_format($order->shipping_cost ?? 0, 2) }} DA</span>
                    </div>
                    @if(($order->discount ?? 0) > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-200">الخصم</span>
                            <span class="text-rose-400">-{{ number_format($order->discount, 2) }} DA</span>
                        </div>
                    @endif
                    <div class="border-t border-dark-800 pt-3 flex justify-between">
                        <span class="text-dark-100 font-bold">الإجمالي</span>
                        <span class="text-dark-100 font-bold text-lg">{{ number_format($order->total, 2) }} DA</span>
                    </div>
                </div>
            </div>

            <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
                <h2 class="text-lg font-bold text-dark-100 mb-4">تحديث الحالة</h2>
                <form action="{{ route('dashboard.orders.updateStatus', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-blue-500 transition-colors">
                        @foreach(['new' => 'جديد', 'confirmed' => 'مؤكد', 'preparing' => 'قيد التجهيز', 'shipped' => 'تم الشحن', 'in_transit' => 'في الطريق', 'delivered' => 'تم التوصيل', 'returned' => 'مسترجع', 'cancelled' => 'ملغي'] as $key => $label)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">تحديث الحالة</button>
                </form>
                @if($order->status !== 'cancelled')
                    <form action="{{ route('dashboard.orders.updateStatus', $order->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="w-full px-4 py-2.5 bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 rounded-xl text-sm font-medium transition-colors">إلغاء الطلب</button>
                    </form>
                @endif
            </div>

            @if(($order->statusHistories ?? collect())->count() > 0)
                <div class="bg-dark-900 rounded-2xl border border-dark-800 p-5">
                    <h2 class="text-lg font-bold text-dark-100 mb-4">سجل الحالات</h2>
                    <div class="space-y-3">
                        @foreach($order->statusHistories->sortByDesc('created_at') as $history)
                            <div class="flex items-start gap-3 pb-3 {{ !$loop->last ? 'border-b border-dark-800' : '' }}">
                                <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 shrink-0"></div>
                                <div>
                                    <p class="text-dark-100 text-sm font-medium">{{ $statusLabels[$history->status] ?? $history->status }}</p>
                                    <p class="text-dark-200 text-xs mt-0.5">{{ $history->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
