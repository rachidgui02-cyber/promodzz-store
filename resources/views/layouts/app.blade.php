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
                    colors: {
                        surface: {
                            DEFAULT: 'var(--surface)',
                            card: 'var(--surface-card)',
                            raised: 'var(--surface-raised)',
                            overlay: 'var(--surface-overlay)',
                        },
                        accent: {
                            blue: '#4f8cff',
                            green: '#34d399',
                            red: '#f87171',
                            orange: '#fb923c',
                            purple: '#a78bfa',
                            pink: '#f472b6',
                            teal: '#2dd4bf',
                            yellow: '#fbbf24',
                        },
                        muted: 'var(--muted)',
                        border: 'var(--border)',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Cairo', sans-serif; }

        /* ═══ Dark Theme (default) ═══ */
        [data-theme="dark"] {
            --surface: #0d0e12;
            --surface-card: #14161d;
            --surface-raised: #1a1c25;
            --surface-overlay: #1e2029;
            --muted: #8a92a6;
            --border: #232530;
            --text-primary: #ffffff;
            --text-secondary: #8a92a6;
            --text-tertiary: #555a6e;
            --sidebar-bg: #111318;
            --sidebar-border: #1e2029;
            --header-bg: rgba(13,14,18,0.85);
            --input-bg: rgba(255,255,255,0.05);
            --input-border: #232530;
            --hover-bg: rgba(255,255,255,0.04);
            --nav-active-bg: rgba(255,255,255,0.08);
            --table-header-bg: rgba(255,255,255,0.03);
            --divider: #1e2029;
            --badge-bg: rgba(255,255,255,0.06);
            --toast-bg: rgba(20,22,29,0.95);
            --modal-bg: #fff;
            --modal-text: #1a1a2e;
            --modal-input-bg: #f1f3f8;
            --modal-input-border: #e2e5ee;
            --modal-label: #374151;
            --modal-secondary: #9ca3af;
            --modal-cancel-bg: #f1f3f8;
            --modal-cancel-text: #374151;
            --scrollbar-track: #0d0e12;
            --scrollbar-thumb: #232530;
            --scrollbar-hover: #33353f;
        }

        /* ═══ Light Theme ═══ */
        [data-theme="light"] {
            --surface: #f5f6fa;
            --surface-card: #ffffff;
            --surface-raised: #f8f9fc;
            --surface-overlay: #f0f1f5;
            --muted: #6b7280;
            --border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e5e7eb;
            --header-bg: rgba(245,246,250,0.85);
            --input-bg: #f3f4f6;
            --input-border: #d1d5db;
            --hover-bg: rgba(0,0,0,0.03);
            --nav-active-bg: rgba(79,140,255,0.08);
            --table-header-bg: #f9fafb;
            --divider: #e5e7eb;
            --badge-bg: rgba(0,0,0,0.05);
            --toast-bg: rgba(255,255,255,0.95);
            --modal-bg: #ffffff;
            --modal-text: #1a1a2e;
            --modal-input-bg: #f3f4f6;
            --modal-input-border: #d1d5db;
            --modal-label: #374151;
            --modal-secondary: #6b7280;
            --modal-cancel-bg: #f3f4f6;
            --modal-cancel-text: #374151;
            --scrollbar-track: #f5f6fa;
            --scrollbar-thumb: #d1d5db;
            --scrollbar-hover: #9ca3af;
        }

        body { background: var(--surface); color: var(--text-primary); transition: background 0.3s, color 0.3s; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--scrollbar-track); }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--scrollbar-hover); }

        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), width 0.3s cubic-bezier(0.4,0,0.2,1); }

        .nav-link { transition: all 0.15s ease; border-right: 3px solid transparent; }
        .nav-link:hover { background: var(--hover-bg); }
        .nav-link.active {
            background: var(--nav-active-bg);
            color: var(--text-primary) !important;
            border-right-color: #4f8cff;
        }

        .nav-dropdown { max-height: 0; overflow: hidden; transition: max-height 0.25s ease, opacity 0.2s ease; opacity: 0; }
        .nav-dropdown.open { max-height: 500px; opacity: 1; }
        .chevron { transition: transform 0.2s ease; }
        .chevron.rotated { transform: rotate(-90deg); }

        @keyframes slideDown { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideUp { from { opacity:1; transform:translateY(0); } to { opacity:0; transform:translateY(-12px); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes pulse-dot { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        .anim-slide-down { animation: slideDown 0.3s ease-out; }
        .anim-fade { animation: fadeIn 0.2s ease-out; }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* Notification toast */
        .toast-enter { animation: slideDown 0.4s ease-out; }
        .toast-exit { animation: slideUp 0.3s ease-in forwards; }

        /* Light mode modal */
        .modal-light { background: var(--modal-bg); color: var(--modal-text); }
        .modal-light input, .modal-light select, .modal-light textarea {
            background: var(--modal-input-bg); border: 1px solid var(--modal-input-border); color: var(--modal-text);
        }
        .modal-light input:focus, .modal-light select:focus, .modal-light textarea:focus {
            outline: none; border-color: #4f8cff; box-shadow: 0 0 0 3px rgba(79,140,255,0.12);
        }

        /* Theme toggle switch */
        .theme-toggle { position: relative; width: 52px; height: 28px; border-radius: 14px; cursor: pointer; transition: background 0.3s; }
        .theme-toggle::after { content: ''; position: absolute; top: 3px; width: 22px; height: 22px; border-radius: 50%; transition: transform 0.3s, background 0.3s; }
        [data-theme="dark"] .theme-toggle { background: #232530; }
        [data-theme="dark"] .theme-toggle::after { right: 3px; background: #4f8cff; }
        [data-theme="light"] .theme-toggle { background: #d1d5db; }
        [data-theme="light"] .theme-toggle::after { right: 27px; background: #fb923c; }
    </style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen">

    {{-- ═══════════════════════════════════════════ --}}
    {{--  IN-APP NOTIFICATION TOAST (Fixed top)     --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-3 pointer-events-none" style="width:90%;max-width:480px"></div>

    <div id="app" class="flex min-h-screen">

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/70 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        {{-- ═══════════════════════════════════════ --}}
        {{--  SIDEBAR                              --}}
        {{-- ═══════════════════════════════════════ --}}
        <aside id="sidebar" class="sidebar-transition fixed top-0 right-0 h-full w-[260px] z-50 flex flex-col overflow-hidden lg:translate-x-0 -translate-x-full" style="background:var(--sidebar-bg);border-left:1px solid var(--sidebar-border)">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color:var(--sidebar-border)">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#4f8cff,#a78bfa)">
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
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 text-sm">

                {{-- لوحة القيادة --}}
                <a href="{{ route('dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    <span class="font-semibold">لوحة القيادة</span>
                </a>

                {{-- الطلبات --}}
                <div>
                    <button onclick="toggleDropdown('orders-dropdown',this)" class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl {{ request()->is('orders*') || request()->is('returns*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                        <div class="flex items-center gap-3">
                            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                            <span class="font-semibold">الطلبات</span>
                        </div>
                        <svg class="chevron w-3.5 h-3.5 {{ request()->is('orders*') || request()->is('returns*') ? 'rotated' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <div id="orders-dropdown" class="nav-dropdown {{ request()->is('orders*') || request()->is('returns*') ? 'open' : '' }} mr-4 space-y-0.5">
                        <a href="{{ route('dashboard.orders.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('dashboard.orders.index') ? 'text-white' : '' }}" style="color:{{ request()->routeIs('dashboard.orders.index') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.orders.index') ? 'bg-accent-blue' : 'bg-gray-600' }}"></span>
                            جميع الطلبات
                        </a>
                        <a href="{{ route('dashboard.returns.scan') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.returns.*') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.returns.*') ? 'bg-accent-blue' : 'bg-gray-600' }}"></span>
                            استلام المرتجعات
                        </a>
                        <a href="{{ route('dashboard.orders.import') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.orders.import') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.orders.import') ? 'bg-accent-blue' : 'bg-gray-600' }}"></span>
                            رفع إكسل
                        </a>
                    </div>
                </div>

                {{-- المنتجات --}}
                <div>
                    <button onclick="toggleDropdown('products-dropdown',this)" class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl {{ request()->is('products*') || request()->is('categories*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                        <div class="flex items-center gap-3">
                            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            <span class="font-semibold">المنتجات</span>
                        </div>
                        <svg class="chevron w-3.5 h-3.5 {{ request()->is('products*') || request()->is('categories*') ? 'rotated' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <div id="products-dropdown" class="nav-dropdown {{ request()->is('products*') || request()->is('categories*') ? 'open' : '' }} mr-4 space-y-0.5">
                        <a href="{{ route('dashboard.products.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.products.index') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.products.index') ? 'bg-accent-blue' : 'bg-gray-600' }}"></span>
                            منتجاتك
                        </a>
                        <a href="{{ route('dashboard.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all" style="color:{{ request()->routeIs('dashboard.categories.index') ? 'var(--text-primary)' : 'var(--text-tertiary)' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('dashboard.categories.index') ? 'bg-accent-blue' : 'bg-gray-600' }}"></span>
                            الفئات
                        </a>
                    </div>
                </div>

                {{-- المخزون --}}
                <a href="{{ route('dashboard.warehouse.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard.warehouse.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25-2.25M12 13.875V7.5"/></svg>
                    <span class="font-semibold">المخزون</span>
                </a>

                {{-- المحفظة (المالية) --}}
                <a href="{{ route('dashboard.stats.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard.stats.index') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    <span class="font-semibold">المحفظة</span>
                </a>

                <div class="border-t my-3" style="border-color:var(--divider)"></div>

                {{-- الشحن --}}
                <a href="{{ route('dashboard.shipping.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard.shipping.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.139-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    <span class="font-semibold">الشحن</span>
                </a>

                {{-- المكالمات --}}
                <a href="{{ route('dashboard.stats.agents') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard.stats.agents') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="font-semibold">المكالمات</span>
                </a>

                {{-- الكوبونات --}}
                <a href="{{ route('dashboard.coupons.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard.coupons.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                    <span class="font-semibold">الكوبونات</span>
                </a>

                <div class="border-t my-3" style="border-color:var(--divider)"></div>

                {{-- الإعدادات --}}
                <a href="{{ route('dashboard.settings.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}" style="color:var(--text-secondary)">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-semibold">الإعدادات</span>
                </a>

            </nav>

            {{-- User + Theme Toggle --}}
            <div class="border-t p-3" style="border-color:var(--sidebar-border)">
                {{-- Theme Toggle --}}
                <div class="flex items-center gap-3 px-2 py-2 mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:var(--badge-bg)">
                        <template x-if="theme === 'dark'"><svg class="w-4 h-4" style="color:#4f8cff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg></template>
                        <template x-if="theme === 'light'"><svg class="w-4 h-4" style="color:#fb923c" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg></template>
                    </div>
                    <button @click="toggleTheme()" class="theme-toggle" :title="theme === 'dark' ? 'الوضع الفاتح' : 'الوضع الداكن'"></button>
                    <span class="text-xs font-semibold" style="color:var(--text-secondary)" x-text="theme === 'dark' ? 'داكن' : 'فاتح'"></span>
                </div>

                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#4f8cff,#a78bfa)">
                        <span class="text-white font-bold text-xs">{{ substr(Auth::user()->name ?? 'م', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color:var(--text-primary)">{{ Auth::user()->name ?? 'مستخدم' }}</p>
                        <p class="text-[11px] truncate" style="color:var(--text-secondary)">{{ Auth::user()->email ?? '' }}</p>
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
                        {{-- Notifications Bell --}}
                        <button class="relative p-2 rounded-xl transition-all" style="color:var(--text-secondary)" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='transparent'">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            <span id="notif-dot" class="absolute top-1.5 left-1.5 w-2 h-2 rounded-full pulse-dot" style="background:#f87171;display:none"></span>
                        </button>
                        {{-- User --}}
                        <div class="flex items-center gap-2 px-2 py-1.5 rounded-xl" style="background:var(--badge-bg)">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:linear-gradient(135deg,#4f8cff,#a78bfa)">
                                <span class="text-white font-bold text-[10px]">{{ substr(Auth::user()->name ?? 'م', 0, 1) }}</span>
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
                    <button onclick="this.parentElement.remove()" class="transition-colors" style="color:#34d399" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="px-4 sm:px-6 pt-4">
                <div class="flex items-center gap-3 p-3.5 rounded-xl anim-slide-down" style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.2)">
                    <svg class="w-5 h-5 flex-shrink-0" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <p class="text-sm flex-1" style="color:#f87171">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="transition-colors" style="color:#f87171">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
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
                theme: localStorage.getItem('theme') || 'dark',
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

        // Toast notification system
        function showToast(icon, title, subtitle, color) {
            color = color || '#4f8cff';
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast-enter pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-full shadow-2xl cursor-pointer';
            toast.style.cssText = `background:var(--toast-bg);backdrop-filter:blur(20px);border:1px solid var(--border);width:100%`;
            toast.onclick = function() { this.classList.add('toast-exit'); setTimeout(() => this.remove(), 300); };
            toast.innerHTML = `
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background:${color}20">
                    <span class="text-lg">${icon}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold truncate" style="color:var(--text-primary)">${title}</p>
                    <p class="text-[11px] truncate" style="color:var(--text-secondary)">${subtitle}</p>
                </div>
                <span class="text-[10px] flex-shrink-0" style="color:var(--text-secondary)">الآن</span>
            `;
            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('toast-exit'); setTimeout(() => toast.remove(), 300); }, 6000);
        }

        // Auto-hide flash messages
        document.querySelectorAll('[id^="flash-"]').forEach(el => {
            setTimeout(() => { el.style.transition = 'opacity 0.3s, transform 0.3s'; el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; setTimeout(() => el.remove(), 300); }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>
