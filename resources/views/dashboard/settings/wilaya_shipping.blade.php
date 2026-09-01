@extends('layouts.app')

@section('title', 'تعريف الشحن حسب الولاية')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" dir="rtl">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-dark-100">تعريف الشحن حسب الولاية</h1>
            <p class="text-dark-400 text-sm mt-1">تحديد تكلفة التوصيل لكل ولاية - التوصيل للمنزل والمكتب</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="#" class="inline-flex items-center gap-2 px-4 py-2.5 bg-dark-800 hover:bg-dark-700 border border-dark-700 text-dark-300 hover:text-dark-100 rounded-xl text-sm font-medium transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.992 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                </svg>
                استعادة الافتراضي
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-dark-400 text-xs">إجمالي الولايات</p>
                    <p class="text-dark-100 text-xl font-bold">{{ count($wilayaRates) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-dark-400 text-xs">متوسط سعر التوصيل للمنزل</p>
                    <p class="text-dark-100 text-xl font-bold">{{ number_format($wilayaRates->avg('domicile_cost'), 0) }} <span class="text-dark-400 text-sm font-normal">DA</span></p>
                </div>
            </div>
        </div>
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                </div>
                <div>
                    <p class="text-dark-400 text-xs">متوسط سعر التوصيل للمكتب</p>
                    <p class="text-dark-100 text-xl font-bold">{{ number_format($wilayaRates->avg('stop_desk_cost'), 0) }} <span class="text-dark-400 text-sm font-normal">DA</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-dark-900 border border-dark-800 rounded-2xl">
        <div class="p-4 border-b border-dark-800">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" id="wilaya-search" placeholder="بحث عن ولاية..." class="w-full bg-dark-800 border border-dark-700 rounded-xl pr-10 pl-4 py-2.5 text-dark-100 text-sm focus:outline-none focus:border-primary-500 transition-colors">
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="toggleAllStatus(true)" class="px-3 py-2 bg-dark-800 hover:bg-dark-700 border border-dark-700 text-dark-300 hover:text-dark-100 rounded-xl text-xs font-medium transition-all">
                        تفعيل الكل
                    </button>
                    <button onclick="toggleAllStatus(false)" class="px-3 py-2 bg-dark-800 hover:bg-dark-700 border border-dark-700 text-dark-300 hover:text-dark-100 rounded-xl text-xs font-medium transition-all">
                        تعطيل الكل
                    </button>
                </div>
            </div>
        </div>

        <form action="{{ route('dashboard.settings.wilayaRates') }}" method="POST" id="wilaya-form">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-dark-800">
                            <th class="text-right px-4 py-3 text-dark-400 text-xs font-semibold uppercase tracking-wider w-12">#</th>
                            <th class="text-right px-4 py-3 text-dark-400 text-xs font-semibold uppercase tracking-wider w-24">كود الولاية</th>
                            <th class="text-right px-4 py-3 text-dark-400 text-xs font-semibold uppercase tracking-wider">اسم الولاية</th>
                            <th class="text-center px-4 py-3 text-dark-400 text-xs font-semibold uppercase tracking-wider w-44">سعر التوصيل للمنزل (DA)</th>
                            <th class="text-center px-4 py-3 text-dark-400 text-xs font-semibold uppercase tracking-wider w-44">سعر التوصيل للمكتب (DA)</th>
                            <th class="text-center px-4 py-3 text-dark-400 text-xs font-semibold uppercase tracking-wider w-24">الحالة</th>
                        </tr>
                    </thead>
                    <tbody id="wilaya-table-body">
                        @php
                            $wilayaNames = [
                                1 => 'أدرار', 2 => 'الشلف', 3 => 'الأغواط', 4 => 'أم البواقي',
                                5 => 'باتنة', 6 => 'بجاية', 7 => 'بسكرة', 8 => 'بشار',
                                9 => 'البليدة', 10 => 'البويرة', 11 => 'تمنراست', 12 => 'تبسة',
                                13 => 'تلمسان', 14 => 'تيارت', 15 => 'تيزي وزو', 16 => 'الجزائر',
                                17 => 'الجلفة', 18 => 'جيجل', 19 => 'سطيف', 20 => 'سعيدة',
                                21 => 'سكيكدة', 22 => 'سيدي بلعباس', 23 => 'عنابة', 24 => 'قالمة',
                                25 => 'قسنطينة', 26 => 'المدية', 27 => 'مستغانم', 28 => 'المسيلة',
                                29 => 'معسكر', 30 => 'ورقلة', 31 => 'وهران', 32 => 'البيض',
                                33 => 'إليزي', 34 => 'برج بوعريريج', 35 => 'بومرداس', 36 => 'الطارف',
                                37 => 'تندوف', 38 => 'تيسمسيلت', 39 => 'الوادي', 40 => 'خنشلة',
                                41 => 'سوق أهراس', 42 => 'تيبازة', 43 => 'ميلة', 44 => 'عين الدفلى',
                                45 => 'النعامة', 46 => 'عين تموشنت', 47 => 'غرداية', 48 => 'غليزان',
                                49 => 'تيميمون', 50 => 'برج باجي مختار', 51 => 'أولاد جلال', 52 => 'بني عباس',
                                53 => 'عين صالح', 54 => 'عين قزام', 55 => 'توقرت', 56 => 'جانت',
                                57 => 'المغير', 58 => 'المنيعة',
                            ];
                        @endphp
                        @foreach($wilayaRates as $index => $rate)
                            @php
                                $code = str_pad($rate->wilaya_code, 2, '0', STR_PAD_LEFT);
                                $name = $wilayaNames[$rate->wilaya_code] ?? 'ولاية ' . $rate->wilaya_code;
                            @endphp
                            <tr class="wilaya-row border-b border-dark-800/50 hover:bg-dark-800/30 transition-colors {{ $index % 2 === 0 ? 'bg-dark-900' : 'bg-dark-900/50' }}" data-wilaya="{{ $code }} {{ $name }}">
                                <td class="px-4 py-3 text-dark-500 text-sm">{{ $code }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-primary-500/10 text-primary-400 text-xs font-bold" dir="ltr">{{ $code }}</span>
                                </td>
                                <td class="px-4 py-3 text-dark-200 text-sm font-medium">{{ $name }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center">
                                        <input type="number" name="rates[{{ $rate->wilaya_code }}][domicile_cost]" value="{{ $rate->domicile_cost }}" min="0" step="50" class="wilaya-input w-28 bg-dark-800 border border-dark-700 rounded-lg px-3 py-2 text-dark-100 text-sm text-center focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30 transition-all">
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center">
                                        <input type="number" name="rates[{{ $rate->wilaya_code }}][stop_desk_cost]" value="{{ $rate->stop_desk_cost }}" min="0" step="50" class="wilaya-input w-28 bg-dark-800 border border-dark-700 rounded-lg px-3 py-2 text-dark-100 text-sm text-center focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30 transition-all">
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="rates[{{ $rate->wilaya_code }}][is_active]" value="1" {{ $rate->is_active ? 'checked' : '' }} class="sr-only peer wilaya-toggle">
                                            <div class="w-10 h-5 bg-dark-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:-translate-x-full rtl:peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:right-[2px] after:bg-dark-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-500 peer-checked:after:bg-white"></div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-dark-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-dark-500 text-sm">
                    عرض <span id="visible-count">{{ count($wilayaRates) }}</span> من {{ count($wilayaRates) }} ولاية
                </p>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-primary-500/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('wilaya-search').addEventListener('input', function(e) {
        const query = e.target.value.trim().toLowerCase();
        const rows = document.querySelectorAll('.wilaya-row');
        let visible = 0;

        rows.forEach(function(row) {
            const text = row.getAttribute('data-wilaya').toLowerCase();
            if (!query || text.includes(query)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('visible-count').textContent = visible;
    });

    function toggleAllStatus(enable) {
        document.querySelectorAll('.wilaya-toggle').forEach(function(toggle) {
            if (!toggle.closest('.wilaya-row').style.display) {
                toggle.checked = enable;
            }
        });
    }
</script>
@endpush
