<!DOCTYPE html>
<html lang="ar" dir="rtl" x-data="themeManager()" :data-theme="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DZCommerce') }} - @yield('title', 'لوحة التحكم')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { cairo: ['Cairo', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Cairo', sans-serif; }

        /* ═══ Light Theme (default) ═══ */
        [data-theme="light"] {
            --surface: #f5f6fa;
            --surface-card: #ffffff;
            --surface-raised: #f8f9fc;
            --muted: #6b7280;
            --border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e5e7eb;
            --sidebar-hover: #f3f4f6;
            --nav-active-bg: #111827;
            --nav-active-text: #ffffff;
            --header-bg: rgba(245,246,250,0.85);
            --hover-bg: rgba(0,0,0,0.03);
            --table-header-bg: #f9fafb;
            --divider: #e5e7eb;
            --input-bg: #f3f4f6;
            --input-border: #d1d5db;
        }

        /* ═══ Dark Theme ═══ */
        [data-theme="dark"] {
            --surface: #0d0e12;
            --surface-card: #14161d;
            --surface-raised: #1a1c25;
            --muted: #8a92a6;
            --border: #232530;
            --text-primary: #ffffff;
            --text-secondary: #8a92a6;
            --text-tertiary: #555a6e;
            --sidebar-bg: #111318;
            --sidebar-border: #1e2029;
            --sidebar-hover: rgba(255,255,255,0.04);
            --nav-active-bg: rgba(255,255,255,0.08);
            --nav-active-text: #ffffff;
            --header-bg: rgba(13,14,18,0.85);
            --hover-bg: rgba(255,255,255,0.04);
            --table-header-bg: rgba(255,255,255,0.03);
            --divider: #1e2029;
            --input-bg: rgba(255,255,255,0.05);
            --input-border: #232530;
        }

        body { background: var(--surface); color: var(--text-primary); transition: background 0.3s, color 0.3s; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), width 0.3s cubic-bezier(0.4,0,0.2,1); }

        /* ═══ Nav Items ═══ */
        .nav-link {
            transition: all 0.15s ease;
            border-radius: 0.75rem;
        }
        .nav-link:hover { background: var(--sidebar-hover); }

        .nav-link.active {
            background: var(--nav-active-bg) !important;
            color: var(--nav-active-text) !important;
        }
        .nav-link.active svg { color: var(--nav-active-text) !important; }
        .nav-link.active span { color: var(--nav-active-text) !important; }

        .nav-dropdown { max-height: 0; overflow: hidden; transition: max-height 0.25s ease, opacity 0.2s ease; opacity: 0; }
        .nav-dropdown.open { max-height: 500px; opacity: 1; }
        .chevron { transition: transform 0.2s ease; }
        .chevron.rotated { transform: rotate(-90deg); }

        @keyframes slideDown { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes pulse-dot { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .anim-slide-down { animation: slideDown 0.3s ease-out; }
        .anim-fade { animation: fadeIn 0.2s ease-out; }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* Theme toggle */
        .theme-toggle { position: relative; width: 44px; height: 24px; border-radius: 12px; cursor: pointer; transition: background 0.3s; }
        .theme-toggle::after { content: ''; position: absolute; top: 2px; width: 20px; height: 20px; border-radius: 50%; transition: transform 0.3s, background 0.3s; }
        [data-theme="dark"] .theme-toggle { background: #232530; }
        [data-theme="dark"] .theme-toggle::after { right: 22px; background: #4f8cff; }
        [data-theme="light"] .theme-toggle { background: #d1d5db; }
        [data-theme="light"] .theme-toggle::after { right: 2px; background: #111827; }

        /* Toast */
        .toast-enter { animation: slideDown 0.4s ease-out; }

        /* Stat cards */
        .stat-card { background: var(--surface-card); border: 1px solid var(--border); border-radius: 1rem; padding: 1.25rem; transition: all 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.05); }

        /* Status modal options */
        .status-option {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 1rem; border-radius: 0.75rem; cursor: pointer;
            transition: all 0.15s; border: 1.5px solid var(--border);
        }
        .status-option:hover { background: var(--hover-bg); border-color: var(--muted); }
    </style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen">

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-3 pointer-events-none" style="width:90%;max-width:480px"></div>

    <div id="app" class="flex min-h-screen">

        {{-- Mobile Overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        {{-- ═══════════════════════════════════════ --}}
        {{--  SIDEBAR — Mega.Market Design         --}}
        {{-- ═══════════════════════════════════════ --}}
        <aside id="sidebar" class="sidebar-transition fixed top-0 right-0 h-full w-[260px] z-50 flex flex-col overflow-hidden lg:translate-x-0 -translate-x-full" style="background:var(--sidebar-bg);border-left:1px solid var(--sidebar-border)">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color:var(--sidebar-border)">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#111827">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight" style="color:var(--text-primary)">Mega.Market</span>
                    <p class="text-[10px] font-medium" style="color:var(--text-secondary)">{{ Auth::user()->shop->name ?? 'DZCommerce Store' }}</p>
                </div>
            </div>

            {{-- Navigation --}}
            @php $userRole = Auth::user()->getRole(); @endphp
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 text-sm">

                {{-- لوحة القيادة --}}
                <a href="{{ route('dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    <span class="font-semibold">لوحة القيادة</span>
                </a>

                {{-- الطلبات --}}
                <div>
                    <button onclick="toggleDropdown('orders-dropdown',this)" class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 {{ request()->is('orders*') || request()->is('returns*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                        <div class="flex items-center gap-3">
                            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                            <span class="font-semibold">الطلبات</span>
                        </div>
                        <svg class="chevron w-3.5 h-3.5 {{ request()->is('orders*') || request()->is('returns*') ? 'rotated' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <div id="orders-dropdown" class="nav-dropdown {{ request()->is('orders*') || request()->is('returns*') ? 'open' : '' }} mr-4 space-y-0.5">
                        <a href="{{ route('dashboard.orders.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.orders.index') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.orders.index') ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                            جميع الطلبات
                        </a>
                        <a href="{{ route('dashboard.returns.scan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.returns.*') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.returns.*') ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                            استلام المرتجعات
                        </a>
                        <a href="{{ route('dashboard.orders.import') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.orders.import') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.orders.import') ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                            رفع إكسل
                        </a>
                    </div>
                </div>

                @if(in_array($userRole, ['owner', 'manager', 'operator']))
                {{-- المنتجات --}}
                <div>
                    <button onclick="toggleDropdown('products-dropdown',this)" class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 {{ request()->is('products*') || request()->is('categories*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                        <div class="flex items-center gap-3">
                            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            <span class="font-semibold">المنتجات</span>
                        </div>
                        <svg class="chevron w-3.5 h-3.5 {{ request()->is('products*') || request()->is('categories*') ? 'rotated' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <div id="products-dropdown" class="nav-dropdown {{ request()->is('products*') || request()->is('categories*') ? 'open' : '' }} mr-4 space-y-0.5">
                        <a href="{{ route('dashboard.products.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.products.index') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.products.index') ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                            منتجاتك
                        </a>
                        <a href="{{ route('dashboard.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.categories.index') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.categories.index') ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                            الفئات
                        </a>
                    </div>
                </div>

                {{-- المخزون --}}
                <a href="{{ route('dashboard.warehouse.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard.warehouse.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25-2.25M12 13.875V7.5"/></svg>
                    <span class="font-semibold">المخزون</span>
                </a>

                {{-- المحاسبة --}}
                @endif
                <a href="{{ route('dashboard.stats.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard.stats.index') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    <span class="font-semibold">المحاسبة</span>
                </a>

                <div class="border-t my-3" style="border-color:var(--divider)"></div>

                {{-- الشحن --}}
                <a href="{{ route('dashboard.shipping.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard.shipping.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.424 48.424 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    <span class="font-semibold">الشحن</span>
                </a>

                {{-- المكالمات --}}
                <a href="{{ route('dashboard.callCenter.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard.callCenter.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="font-semibold">مركز الاتصال</span>
                </a>

                {{-- الكوبونات --}}
                <a href="{{ route('dashboard.coupons.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard.coupons.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                    <span class="font-semibold">الكوبونات</span>
                </a>

                @if(in_array($userRole, ['owner', 'manager']))
                <div class="border-t my-3" style="border-color:var(--divider)"></div>

                {{-- الإعدادات --}}
                <a href="{{ route('dashboard.settings.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-semibold">الإعدادات</span>
                </a>
                @endif

            </nav>

            {{-- ═══════════ Bottom Section ═══════════ --}}
            <div class="border-t p-3 space-y-2" style="border-color:var(--sidebar-border)">

                {{-- Theme Toggle --}}
                <div class="flex items-center gap-3 px-3 py-2 rounded-xl" style="background:var(--hover-bg)">
                    <svg x-show="theme === 'dark'" class="w-4 h-4" style="color:var(--text-secondary)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    <svg x-show="theme === 'light'" class="w-4 h-4" style="color:var(--text-secondary)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    <button @click="toggleTheme()" class="theme-toggle"></button>
                    <span class="text-xs font-semibold" style="color:var(--text-secondary)" x-text="theme === 'dark' ? 'داكن' : 'فاتح'"></span>
                </div>

                {{-- عرض المتجر --}}
                <a href="{{ route('storefront.show', Auth::user()->shop->slug ?? 'dzcommerce-store') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all" style="color:var(--text-secondary)" onmouseover="this.style.background='var(--sidebar-hover)'" onmouseout="this.style.background='transparent'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    <span class="text-xs font-semibold">عرض المتجر</span>
                </a>

                {{-- User + Logout --}}
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:#111827">
                        <span class="text-white font-bold text-[10px]">{{ substr(Auth::user()->name ?? 'م', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold truncate" style="color:var(--text-primary)">{{ Auth::user()->name ?? 'مستخدم' }}</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-[10px] truncate" style="color:var(--text-secondary)">{{ Auth::user()->email ?? '' }}</p>
                            @php
                                $roleLabels = ['owner' => 'المالك', 'manager' => 'المدير', 'operator' => 'المشغل', 'viewer' => 'مشاهد'];
                                $roleColors = ['owner' => '#4f8cff', 'manager' => '#a78bfa', 'operator' => '#34d399', 'viewer' => '#8a92a6'];
                                $currentRole = Auth::user()->getRole();
                            @endphp
                            <span class="text-[8px] font-bold px-1.5 py-0.5 rounded-full" style="background:{{ $roleColors[$currentRole] ?? '#8a92a6' }}20;color:{{ $roleColors[$currentRole] ?? '#8a92a6' }}">{{ $roleLabels[$currentRole] ?? $currentRole }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg transition-all" style="color:var(--text-tertiary)" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='var(--text-tertiary)'" title="تسجيل الخروج">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ═══════════════════════════════════════ --}}
        {{--  MAIN CONTENT                         --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="flex-1 lg:mr-[260px] min-h-screen flex flex-col">

            {{-- Header --}}
            <header class="sticky top-0 z-30" style="background:var(--header-bg);backdrop-filter:blur(20px);border-bottom:1px solid var(--divider)">
                <div class="flex items-center justify-between px-4 sm:px-6 py-3.5">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg" style="color:var(--text-secondary)" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='transparent'">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        </button>
                        <h1 class="text-lg sm:text-xl font-extrabold" style="color:var(--text-primary)">@yield('title', 'لوحة التحكم')</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl" style="background:var(--input-bg)">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background:#111827">
                                <span class="text-white font-bold text-[9px]">{{ substr(Auth::user()->name ?? 'م', 0, 1) }}</span>
                            </div>
                            <span class="text-xs font-semibold hidden sm:block" style="color:var(--text-primary)">{{ Auth::user()->name ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="px-4 sm:px-6 pt-4">
                <div class="flex items-center gap-3 p-3.5 rounded-xl anim-slide-down" style="background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.2)">
                    <svg class="w-5 h-5 flex-shrink-0" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm flex-1" style="color:#34d399">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="transition-colors" style="color:#34d399"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="px-4 sm:px-6 pt-4">
                <div class="flex items-center gap-3 p-3.5 rounded-xl anim-slide-down" style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.2)">
                    <svg class="w-5 h-5 flex-shrink-0" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <p class="text-sm flex-1" style="color:#f87171">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="transition-colors" style="color:#f87171"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function themeManager() {
            return {
                theme: localStorage.getItem('theme') || 'light',
                toggleTheme() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('theme', this.theme);
                }
            }
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }

        function toggleDropdown(id, btn) {
            document.getElementById(id).classList.toggle('open');
            btn.querySelector('.chevron').classList.toggle('rotated');
        }

        function showToast(icon, title, subtitle, color) {
            color = color || '#4f8cff';
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast-enter pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-full shadow-2xl cursor-pointer';
            toast.style.cssText = 'background:white;border:1px solid #e5e7eb;width:100%';
            toast.onclick = function() { this.remove(); };
            toast.innerHTML = `
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background:${color}15">
                    <span class="text-lg">${icon}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">${title}</p>
                    <p class="text-[11px] text-gray-500 truncate">${subtitle}</p>
                </div>
                <span class="text-[10px] text-gray-400 flex-shrink-0">الآن</span>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 6000);
        }

        // ═══════ Real-Time Order Notifications ═══════
        let lastOrderCount = 0;
        let orderSound = null;

        function initOrderNotifications() {
            // Only on dashboard page
            if (!document.querySelector('[data-poll-orders]')) return;

            // Preload sound
            orderSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbsGczIj2NysijaTklQ5WJfi4nUZaGdi0qWJuMeC4rXZ6Oey8tY6CRfTAuZ6GUfzIxarCdhDIyb7OdhjUzc7SfhzY0dbaehjY1ebiggTY2fL2jgTU3gb+kgDU5hMGmfilOeJ2RYC9iiKedWBpXipaqZhZLg4eUXjVYj5+baC9fjZ+gbTNhi5+caTRkip6bZjJnjp2aZTRqkZ2ZZTVskp2ZZTZtk56ZZTduj52ZZTdukJ6ZZTdukZ+aZjdvlJ6aZjhwlZ+aZjpxlp+aZjtxl5+aZj1zmJ+aZj10mZ6aZj51mp6aZj92m56aZkF3nJ2aZkF4nZ2aZkF5np2aZkF6np2aZkF7n52aZkF8oJ2aZkF9oZ2aZkF+op2aZkGAo52aZkGBpJ2aZkGCpZ2aZkGDpp2aZkGEp52aZkGJqJ2aZkGKqZ2aZkGLqp2aZkGMq52aZkGNrJ6aZkGOraCaaEGPraGaaEGRr6OaaEGSsKSacEKTs6WbcEKUtaedcUKVt6mfdUKWuKqgd0KYuayheEKZuq6ieUKau7CkfkKbu7GlgEKcvLKlgkKdvbOmg0KevrSmg0KfvrWnhEKgv7aohkKhwLeoh0Khwbiph0KjwtiqiEKiwtmriUKiwtqti0Kjwtuu | head -c 500)).play().catch(()=>{});

            setInterval(checkNewOrders, 30000);
        }

        function checkNewOrders() {
            fetch('/api/stats', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (lastOrderCount > 0 && data.today_new_orders > lastOrderCount) {
                        const diff = data.today_new_orders - lastOrderCount;
                        showToast('🔔', 'طلب جديد!', `${diff} طلب جديد وصل`, '#34d399');
                        playNotificationSound();
                    }
                    lastOrderCount = data.today_new_orders || 0;
                })
                .catch(() => {});
        }

        function playNotificationSound() {
            try {
                if (!orderSound) return;
                orderSound.currentTime = 0;
                orderSound.volume = 0.5;
                orderSound.play().catch(() => {});
            } catch(e) {}
        }

        document.addEventListener('DOMContentLoaded', initOrderNotifications);
    </script>
    @stack('scripts')
</body>
</html>
