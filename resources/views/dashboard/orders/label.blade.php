@extends('layouts.app')

@section('title', 'ملصق الشحن')

@section('content')
<div class="flex justify-center py-8" dir="rtl">
    <div id="shipping-label" class="bg-white text-black w-[105mm] min-h-[148mm] p-4 font-sans shadow-2xl rounded-lg" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <div class="text-center border-b-2 border-black pb-3 mb-3">
            <h1 class="text-lg font-bold">DZCommerce Store</h1>
            <p class="text-xs text-gray-600">متجر إلكتروني</p>
        </div>

        <div class="text-center mb-3">
            <p class="text-xs text-gray-500">رقم الطلب</p>
            <p class="text-base font-bold">#{{ $order->order_number }}</p>
            <svg id="barcode" class="mx-auto mt-1"></svg>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="border border-gray-300 rounded p-2">
                <p class="text-[10px] text-gray-500 font-bold mb-1">المرسل</p>
                <p class="text-xs font-bold">DZCommerce Store</p>
                <p class="text-[10px] text-gray-600">الجزائر العاصمة</p>
            </div>
            <div class="border border-gray-300 rounded p-2">
                <p class="text-[10px] text-gray-500 font-bold mb-1">المستلم</p>
                <p class="text-xs font-bold">{{ $order->customer_name }}</p>
                <p class="text-[10px] text-gray-600" dir="ltr">{{ $order->customer_phone }}</p>
            </div>
        </div>

        <div class="border border-gray-300 rounded p-2 mb-3">
            <p class="text-[10px] text-gray-500 font-bold mb-1">عنوان التوصيل</p>
            <p class="text-xs">{{ $order->customer_address }}</p>
            <p class="text-[10px] text-gray-600">{{ $order->wilaya }}{{ $order->commune ? ' - ' . $order->commune : '' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="bg-gray-100 rounded p-2 text-center">
                <p class="text-[10px] text-gray-500">عدد المنتجات</p>
                <p class="text-sm font-bold">{{ $order->items->sum('quantity') }}</p>
            </div>
            <div class="bg-gray-100 rounded p-2 text-center">
                <p class="text-[10px] text-gray-500">المبلغ عند التوصيل</p>
                <p class="text-sm font-bold">{{ number_format($order->total, 2) }} DA</p>
            </div>
        </div>

        <div class="border-t-2 border-dashed border-gray-300 pt-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[10px] text-gray-500">نوع الدفع</p>
                    <p class="text-xs font-bold">الدفع عند الاستلام (COD)</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500">ملاحظات</p>
                    <p class="text-xs">{{ $order->notes ?? 'لا توجد ملاحظات' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center border-t border-gray-200 pt-2">
            <p class="text-[9px] text-gray-400">تم الطباعة في {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<div class="flex justify-center pb-8">
    <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2 print:hidden">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        طباعة الملصق
    </button>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #shipping-label, #shipping-label * {
            visibility: visible;
        }
        #shipping-label {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            box-shadow: none;
            border: 1px solid #ccc;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        JsBarcode("#barcode", "{{ $order->order_number }}", {
            format: "CODE128",
            width: 1.5,
            height: 30,
            displayValue: true,
            fontSize: 12,
            margin: 5
        });
    });
</script>
@endsection
