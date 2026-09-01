@extends('layouts.app')
@section('title', 'المخزون والمحاسبة')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<div class="space-y-6" dir="rtl" x-data="{ activeTab: 'financial', showModal: false, showCostModal: false, selectedProduct: null }">

    {{-- Period Filters --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">المخزون والمحاسبة</h1>
        <div class="flex items-center gap-2 flex-wrap">
            @foreach(['all'=>'الكل','today'=>'اليوم','yesterday'=>'أمس','7days'=>'7 أيام','30days'=>'30 يوم'] as $key => $label)
                <a href="?period={{ $key }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all" style="{{ request('period','all') === $key ? 'background:linear-gradient(135deg,#4f8cff,#a78bfa);color:var(--text-primary)' : 'background:rgba(255,255,255,0.04);color:#8a92a6' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    {{-- ═══════ Tab Navigation ═══════ --}}
    <div class="flex border-b overflow-x-auto gap-0" style="border-color:var(--border)">
        <button @click="activeTab = 'financial'" class="px-5 py-3 text-sm font-bold transition-colors whitespace-nowrap border-b-2" :style="activeTab === 'financial' ? 'color:#4f8cff;border-bottom-color:#4f8cff' : 'color:#8a92a6;border-bottom-color:transparent'">المالية</button>
        <button @click="activeTab = 'products'" class="px-5 py-3 text-sm font-bold transition-colors whitespace-nowrap border-b-2" :style="activeTab === 'products' ? 'color:#4f8cff;border-bottom-color:#4f8cff' : 'color:#8a92a6;border-bottom-color:transparent'">أرباح المنتجات</button>
        <button @click="activeTab = 'charts'" class="px-5 py-3 text-sm font-bold transition-colors whitespace-nowrap border-b-2" :style="activeTab === 'charts' ? 'color:#4f8cff;border-bottom-color:#4f8cff' : 'color:#8a92a6;border-bottom-color:transparent'">الرسوم البيانية</button>
        <a href="{{ route('dashboard.warehouse.expenses') }}" class="px-5 py-3 text-sm font-bold transition-colors whitespace-nowrap" style="color:var(--text-secondary)">المصاريف</a>
        <a href="{{ route('dashboard.warehouse.wallet') }}" class="px-5 py-3 text-sm font-bold transition-colors whitespace-nowrap" style="color:var(--text-secondary)">المحفظة</a>
        <a href="{{ route('dashboard.warehouse.orders') }}" class="px-5 py-3 text-sm font-bold transition-colors whitespace-nowrap" style="color:var(--text-secondary)">الطلبيات</a>
    </div>

    {{-- ═══════ Financial Tab ═══════ --}}
    <div x-show="activeTab === 'financial'" x-cloak>
        {{-- Revenue & Profit Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="surface-card rounded-2xl border p-4 relative overflow-hidden" style="border-color:var(--border)">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-blue to-accent-purple"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(79,140,255,0.12)"><svg class="w-4 h-4" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">رقم الأعمال</span>
                </div>
                <div class="text-2xl font-extrabold" style="color:#4f8cff">{{ number_format($summary['gross_revenue']) }}</div>
                <div class="text-xs mt-1" style="color:var(--text-tertiary)">د.ج — سعر بيع + شحن</div>
            </div>
            <div class="surface-card rounded-2xl border p-4 relative overflow-hidden" style="border-color:var(--border)">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-green to-accent-teal"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(52,211,153,0.12)"><svg class="w-4 h-4" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg></div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">صافي الربح</span>
                </div>
                <div class="text-2xl font-extrabold" style="color:{{ $summary['net_profit'] >= 0 ? '#34d399' : '#f87171' }}">{{ number_format($summary['net_profit']) }}</div>
                <div class="text-xs mt-1" style="color:var(--text-tertiary)">د.ج — رقم الأعمال - (المصاريف + التكلفة)</div>
            </div>
            <div class="surface-card rounded-2xl border p-4 relative overflow-hidden" style="border-color:var(--border)">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-orange to-accent-yellow"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(251,146,60,0.12)"><svg class="w-4 h-4" style="color:#fb923c" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">العائد على الاستثمار</span>
                </div>
                <div class="text-2xl font-extrabold" style="color:#fb923c">{{ $summary['roi'] }}%</div>
                <div class="text-xs mt-1" style="color:var(--text-tertiary)">{{ $summary['successful_orders'] }} طلب ناجح</div>
            </div>
            <div class="surface-card rounded-2xl border p-4 relative overflow-hidden" style="border-color:var(--border)">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-red to-accent-pink"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(248,113,113,0.12)"><svg class="w-4 h-4" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg></div>
                    <span class="text-xs font-medium" style="color:var(--text-secondary)">المصاريف + التكلفة</span>
                </div>
                <div class="text-2xl font-extrabold" style="color:#f87171">{{ number_format($summary['total_expenses'] + $summary['product_cost']) }}</div>
                <div class="text-xs mt-1" style="color:var(--text-tertiary)">د.ج — مصاريف + تكلفة منتجات</div>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="surface-card rounded-2xl border p-4" style="border-color:var(--border)">
                <span class="text-xs font-medium" style="color:var(--text-secondary)">صافي المبيعات</span>
                <div class="text-xl font-extrabold mt-1" style="color:var(--text-primary)">{{ number_format($summary['net_product_sales']) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            </div>
            <div class="surface-card rounded-2xl border p-4" style="border-color:var(--border)">
                <span class="text-xs font-medium" style="color:var(--text-secondary)">المصاريف</span>
                <div class="text-xl font-extrabold mt-1" style="color:#f87171">{{ number_format($summary['total_expenses']) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            </div>
            <div class="surface-card rounded-2xl border p-4" style="border-color:var(--border)">
                <span class="text-xs font-medium" style="color:var(--text-secondary)">تكلفة المنتجات</span>
                <div class="text-xl font-extrabold mt-1" style="color:#fb923c">{{ number_format($summary['product_cost']) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            </div>
            <div class="surface-card rounded-2xl border p-4" style="border-color:var(--border)">
                <span class="text-xs font-medium" style="color:var(--text-secondary)">رأس المال في المخزن</span>
                <div class="text-xl font-extrabold mt-1" style="color:#a78bfa">{{ number_format($summary['capital_in_stock']) }} <span class="text-xs" style="color:var(--text-tertiary)">DA</span></div>
            </div>
        </div>
    </div>

    {{-- ═══════ Products Profitability Tab ═══════ --}}
    <div x-show="activeTab === 'products'" x-cloak>
        <div class="surface-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
            <div class="px-5 py-4 border-b" style="border-color:var(--border)">
                <h3 class="text-sm font-bold" style="color:var(--text-primary)">جدول أرباح المنتجات</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr style="background:var(--table-header-bg)">
                        <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">المنتج</th>
                        <th class="text-center px-3 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الوحدات المباعة</th>
                        <th class="text-center px-3 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجمالي الربح</th>
                        <th class="text-center px-3 py-3 font-semibold text-xs cursor-pointer" style="color:#fb923c" @click="showCostModal = true; selectedProduct = null">متوسط التكلفة</th>
                        <th class="text-center px-3 py-3 font-semibold text-xs" style="color:#34d399">متوسط الربح</th>
                    </tr></thead>
                    <tbody class="divide-y" style="border-color:var(--border)">
                        @forelse($productProfits as $p)
                            <tr class="transition-colors hover:bg-white/[0.02]">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($p['image'])<img src="{{ asset('storage/'.$p['image']) }}" class="w-9 h-9 rounded-lg object-cover" style="border:1px solid #232530">@else<div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background:rgba(255,255,255,0.04);border:1px solid #232530"><svg class="w-4 h-4" style="color:var(--text-tertiary)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25-2.25M12 13.875V7.5"/></svg></div>@endif
                                        <div><span class="font-semibold text-sm" style="color:var(--text-primary)">{{ $p['name'] }}</span><div class="text-xs" style="color:var(--text-tertiary)">{{ number_format($p['sell_price']) }} DA × {{ $p['units_sold'] }}</div></div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center"><span class="font-bold" style="color:#4f8cff">{{ $p['units_sold'] }}</span></td>
                                <td class="px-3 py-3 text-center"><span class="font-bold" style="color:{{ $p['total_profit'] >= 0 ? '#34d399' : '#f87171' }}">+{{ number_format($p['total_profit']) }}</span></td>
                                <td class="px-3 py-3 text-center">
                                    <button @click="showCostModal = true; selectedProduct = {{ json_encode($p) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all hover:scale-[1.02]" style="background:rgba(251,146,60,0.12);color:#fb923c">{{ number_format($p['avg_cost']) }} DA</button>
                                </td>
                                <td class="px-3 py-3 text-center"><span class="font-bold" style="color:#34d399">+{{ number_format($p['avg_profit']) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا توجد مبيعات بعد</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════ Charts Tab ═══════ --}}
    <div x-show="activeTab === 'charts'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="surface-card rounded-2xl border p-5" style="border-color:var(--border)">
                <h3 class="text-sm font-bold mb-4" style="color:var(--text-primary)">صافي الربح الشهري</h3>
                <div style="height:280px"><canvas id="monthlyProfitChart"></canvas></div>
            </div>
            <div class="surface-card rounded-2xl border p-5" style="border-color:var(--border)">
                <h3 class="text-sm font-bold mb-4" style="color:var(--text-primary)">توزيع حالات الطلبات</h3>
                <div style="height:280px"><canvas id="statusPieChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- ═══════ Cost Breakdown Modal ═══════ --}}
    <div x-show="showCostModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div @click.away="showCostModal = false; selectedProduct = null" x-show="showCostModal" x-transition class="modal-light rounded-2xl shadow-2xl w-full max-w-lg" x-cloak>
            <div class="p-6" x-show="selectedProduct">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-extrabold" style="color:#1a1a2e">تفصيل التكلفة — <span x-text="selectedProduct?.name"></span></h3>
                    <button @click="showCostModal = false; selectedProduct = null" class="p-1 rounded-lg" style="color:#9ca3af"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1"><span style="color:#374151">تكلفة الشراء (الوحدة)</span><span class="font-bold" style="color:#1a1a2e"><span x-text="selectedProduct?.buy_price?.toLocaleString()"></span> DA</span></div>
                        <div class="w-full rounded-full h-3" style="background:#f1f3f8"><div class="h-3 rounded-full" style="background:#4f8cff;width:100%"></div></div>
                        <p class="text-xs mt-1" style="color:#9ca3af">100% — التكلفة الأساسية للمنتج</p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1"><span style="color:#374151">سعر البيع (الوحدة)</span><span class="font-bold" style="color:#1a1a2e"><span x-text="selectedProduct?.sell_price?.toLocaleString()"></span> DA</span></div>
                        <div class="w-full rounded-full h-3" style="background:#f1f3f8"><div class="h-3 rounded-full" style="background:#34d399;width:100%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1"><span style="color:#374151">متوسط الربح (الوحدة)</span><span class="font-bold" style="color:#34d399">+<span x-text="selectedProduct?.avg_profit?.toLocaleString()"></span> DA</span></div>
                    </div>
                    <div class="border-t pt-4" style="border-color:#e5e7eb">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="rounded-xl p-3" style="background:#f1f3f8"><div class="text-lg font-extrabold" style="color:#4f8cff" x-text="selectedProduct?.units_sold"></div><div class="text-xs" style="color:#9ca3af">وحدة مباعة</div></div>
                            <div class="rounded-xl p-3" style="background:#f1f3f8"><div class="text-lg font-extrabold" style="color:#34d399" x-text="'+' + selectedProduct?.total_profit?.toLocaleString()"></div><div class="text-xs" style="color:#9ca3af">إجمالي الربح (DA)</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const chartData = @json($chartData);
const monthlyCtx = document.getElementById('monthlyProfitChart');
if (monthlyCtx) {
    new Chart(monthlyCtx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: chartData.monthly_profit.map(m => m.month),
            datasets: [{
                label: 'صافي الربح',
                data: chartData.monthly_profit.map(m => m.profit),
                backgroundColor: chartData.monthly_profit.map(m => m.profit >= 0 ? '#34d399' : '#f87171'),
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#8a92a6', font: { size: 11, family: 'Cairo' } }, grid: { display: false } },
                y: { ticks: { color: '#8a92a6', font: { size: 10, family: 'Cairo' }, callback: v => v.toLocaleString() }, grid: { color: '#232530' } }
            }
        }
    });
}
const statusCtx = document.getElementById('statusPieChart');
if (statusCtx) {
    const dist = chartData.status_distribution;
    new Chart(statusCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['مُسلّم', 'شُحن', 'في التوصيل', 'مرتجع', 'ملغي'],
            datasets: [{ data: [dist.delivered||0, dist.shipped||0, dist.out_for_delivery||0, dist.returned||0, dist.cancelled||0], backgroundColor: ['#34d399','#4f8cff','#2dd4bf','#f87171','#6b7280'], borderWidth: 0, hoverOffset: 8 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { color: '#8a92a6', font: { size: 11, family: 'Cairo' }, padding: 16 } } } }
    });
}
</script>
@endpush
@endsection
