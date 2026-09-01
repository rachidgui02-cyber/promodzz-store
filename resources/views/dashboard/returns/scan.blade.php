@extends('layouts.app')
@section('title', 'استلام المرتجعات')
@push('styles')
<style>
    .scan-input { font-size: 1.5rem; text-align: center; letter-spacing: 0.1em; }
    .scan-input:focus { box-shadow: 0 0 0 3px rgba(79,140,255,0.3); }
    .result-card { animation: scanSlideUp 0.3s ease; }
    @keyframes scanSlideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .pulse-ring { animation: pulseRing 1.5s infinite; }
    @keyframes pulseRing { 0%{box-shadow:0 0 0 0 rgba(79,140,255,0.4)} 70%{box-shadow:0 0 0 15px rgba(79,140,255,0)} 100%{box-shadow:0 0 0 0 rgba(79,140,255,0)} }
</style>
@endpush
@section('content')
<div class="max-w-xl mx-auto space-y-6" dir="rtl">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:#111827;color:#ffffff">
            <svg class="w-6 h-6" style="color:var(--text-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold" style="color:var(--text-primary)">استلام المرتجعات</h1>
            <p class="text-sm" style="color:var(--text-secondary)">اسكان باركود الطلبية لإرجاع المنتج للمخزن تلقائياً</p>
        </div>
    </div>
    <div class="stat-card rounded-2xl border p-6" style="border-color:var(--border)">
        <div class="text-center mb-4">
            <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-3 pulse-ring" style="background:rgba(79,140,255,0.12)">
                <svg class="w-8 h-8" style="color:#4f8cff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
            <p class="text-sm" style="color:var(--text-secondary)">اسكان الباركود أو اكتب رقم الطلب يدوياً</p>
        </div>
        <form id="scanForm" onsubmit="return processReturn(event)">
            <input type="text" id="scanInput" class="scan-input w-full rounded-2xl px-6 py-5 font-mono focus:outline-none transition-colors" style="background:var(--input-bg);border:1px solid var(--border);color:var(--text-primary)" placeholder="رقم الطلب أو رقم التتبع" autofocus autocomplete="off">
            <button type="submit" class="w-full mt-3 py-3 rounded-xl font-bold text-sm transition-all hover:scale-[1.01]" style="background:#111827;color:#ffffff">استلام المرتجع</button>
        </form>
    </div>
    <div id="resultArea"></div>
    <div class="stat-card rounded-2xl border p-5" style="border-color:var(--border)">
        <h3 class="font-bold text-sm mb-3" style="color:var(--text-primary)">آخر المرتجعات</h3>
        <div id="recentReturns" class="space-y-2">
            <p class="text-xs text-center py-4" style="color:var(--text-tertiary)">ابدأ بالاسكان لعرض النتائج هنا</p>
        </div>
    </div>
</div>
<script>
var recentList = [];
function processReturn(e) {
    e.preventDefault();
    var input = document.getElementById('scanInput');
    var code = input.value.trim();
    if (!code) return false;
    input.disabled = true; input.style.opacity = '0.5';
    fetch('/returns/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
        body: JSON.stringify({ code: code })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        input.disabled = false; input.style.opacity = '1'; input.value = ''; input.focus();
        var area = document.getElementById('resultArea');
        var borderColor = data.success ? 'rgba(52,211,153,0.3)' : 'rgba(248,113,113,0.3)';
        var bgColor = data.success ? 'rgba(52,211,153,0.05)' : 'rgba(248,113,113,0.05)';
        var iconColor = data.success ? '#34d399' : '#f87171';
        var icon = data.success
            ? '<svg class="w-8 h-8" style="color:'+iconColor+'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            : '<svg class="w-8 h-8" style="color:'+iconColor+'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        var html = '<div class="result-card stat-card rounded-2xl border p-5" style="border-color:'+borderColor+';background:'+bgColor+'"><div class="flex items-start gap-3"><div class="flex-shrink-0">' + icon + '</div><div class="flex-1"><p class="font-bold text-sm" style="color:var(--text-primary)">' + data.message + '</p>';
        if (data.success && data.order) {
            html += '<div class="mt-3 grid grid-cols-2 gap-2 text-xs">' +
                '<div class="rounded-lg p-2" style="background:rgba(255,255,255,0.04)"><span style="color:var(--text-secondary)">الطلب:</span> <span class="font-mono" style="color:var(--text-primary)">#' + data.order.number + '</span></div>' +
                '<div class="rounded-lg p-2" style="background:rgba(255,255,255,0.04)"><span style="color:var(--text-secondary)">العميل:</span> <span style="color:var(--text-primary)">' + data.order.customer + '</span></div>' +
                '<div class="rounded-lg p-2" style="background:rgba(255,255,255,0.04)"><span style="color:var(--text-secondary)">الهاتف:</span> <span dir="ltr" style="color:var(--text-primary)">' + data.order.phone + '</span></div>' +
                '<div class="rounded-lg p-2" style="background:rgba(255,255,255,0.04)"><span style="color:var(--text-secondary)">الولاية:</span> <span style="color:var(--text-primary)">' + data.order.wilaya + '</span></div>' +
                '<div class="rounded-lg p-2" style="background:rgba(255,255,255,0.04)"><span style="color:var(--text-secondary)">المنتج:</span> <span style="color:var(--text-primary)">' + data.order.product + ' ×' + data.order.quantity + '</span></div>' +
                '<div class="rounded-lg p-2" style="background:rgba(255,255,255,0.04)"><span style="color:var(--text-secondary)">المبلغ:</span> <span class="font-bold" style="color:var(--text-primary)">' + data.order.total + ' د.ج</span></div></div>';
            recentList.unshift(data.order); renderRecent();
        }
        html += '</div></div></div>'; area.innerHTML = html;
        setTimeout(function() { area.innerHTML = ''; }, 8000);
    })
    .catch(function() { input.disabled = false; input.style.opacity = '1'; });
    return false;
}
function renderRecent() {
    var container = document.getElementById('recentReturns');
    if (recentList.length === 0) return;
    var html = '';
    recentList.slice(0, 5).forEach(function(r) {
        html += '<div class="flex items-center justify-between rounded-xl px-4 py-2" style="background:rgba(255,255,255,0.03)">' +
            '<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full" style="background:#f87171"></span><span class="text-xs font-mono" style="color:var(--text-primary)">#' + r.number + '</span><span class="text-xs" style="color:var(--text-secondary)">' + r.customer + '</span></div>' +
            '<span class="text-xs font-bold" style="color:#f87171">' + r.total + ' د.ج</span></div>';
    });
    container.innerHTML = html;
}
</script>
@endsection
