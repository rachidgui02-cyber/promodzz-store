@extends('layouts.app')
@section('title', 'العمال')
@section('content')
<div class="space-y-6" dir="rtl" x-data="{ showModal: false }">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-white">العمال</h1>
        <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all hover:scale-[1.02]" style="background:#111827;color:#ffffff">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة عامل
        </button>
    </div>
    <div class="stat-card rounded-2xl border overflow-hidden" style="border-color:var(--border)">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr style="background:rgba(255,255,255,0.03)">
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الاسم</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">البريد الإلكتروني</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الهاتف</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الدور</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">الحالة</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">آخر دخول</th>
                    <th class="text-right px-5 py-3 font-semibold text-xs" style="color:var(--text-secondary)">إجراءات</th>
                </tr></thead>
                <tbody class="divide-y" style="border-color:var(--border)">
                    @forelse($employees ?? [] as $employee)
                        @php
                            $roleLabels = ['manager' => 'مدير', 'operator' => 'مشغل', 'viewer' => 'مشاهد'];
                            $roleColors = ['manager' => '#a78bfa', 'operator' => '#4f8cff', 'viewer' => '#6b7280'];
                            $rc = $roleColors[$employee->role] ?? '#6b7280';
                        @endphp
                        <tr class="transition-colors" onmouseover="this.style.background=var(--hover-bg)" onmouseout="this.style.background='transparent'">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background:linear-gradient(135deg,#4f8cff,#a78bfa)">{{ mb_substr($employee->name, 0, 1) }}</div>
                                    <span class="font-medium text-white">{{ $employee->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)" dir="ltr">{{ $employee->email }}</td>
                            <td class="px-5 py-3" style="color:var(--text-secondary)" dir="ltr">{{ $employee->phone ?? '-' }}</td>
                            <td class="px-5 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:{{ $rc }}20;color:{{ $rc }}">{{ $roleLabels[$employee->role] ?? $employee->role }}</span></td>
                            <td class="px-5 py-3">
                                @if($employee->is_active)<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(52,211,153,0.12);color:#34d399">نشط</span>@else<span class="px-2.5 py-1 rounded-full text-xs font-medium" style="background:rgba(107,114,128,0.12);color:#6b7280">غير نشط</span>@endif
                            </td>
                            <td class="px-5 py-3 text-xs" style="color:var(--text-secondary)">{{ $employee->last_login_at ? $employee->last_login_at->diffForHumans() : 'لم يسجل دخول بعد' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('dashboard.employees.toggle', $employee->id) }}" method="POST">@csrf @method('PATCH')
                                        <button type="submit" class="transition-colors" style="color:{{ $employee->is_active ? '#fb923c' : '#34d399' }}" title="{{ $employee->is_active ? 'تعطيل' : 'تفعيل' }}">
                                            @if($employee->is_active)<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>@else<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>@endif
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العامل؟')">@csrf @method('DELETE')
                                        <button type="submit" class="transition-colors" style="color:#f87171" title="حذف"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center" style="color:var(--text-tertiary)">لا يوجد عمال بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px)">
        <div @click.away="showModal = false" x-show="showModal" x-transition class="modal-light rounded-2xl shadow-2xl w-full max-w-md" x-cloak>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-extrabold" style="color:#1a1a2e">إضافة عامل جديد</h3>
                    <button @click="showModal = false" class="p-1 rounded-lg" style="color:#9ca3af"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form action="{{ route('dashboard.employees.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">الاسم *</label><input type="text" name="name" required class="w-full rounded-xl px-4 py-3 text-sm font-medium" placeholder="اسم العامل">@error('name')<span class="text-sm mt-1 block" style="color:#f87171">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">البريد الإلكتروني *</label><input type="email" name="email" required class="w-full rounded-xl px-4 py-3 text-sm font-medium" dir="ltr" placeholder="email@example.com">@error('email')<span class="text-sm mt-1 block" style="color:#f87171">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">الهاتف</label><input type="text" name="phone" class="w-full rounded-xl px-4 py-3 text-sm font-medium" dir="ltr" placeholder="0555123456"></div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">كلمة المرور *</label><input type="password" name="password" required class="w-full rounded-xl px-4 py-3 text-sm font-medium" placeholder="••••••••">@error('password')<span class="text-sm mt-1 block" style="color:#f87171">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-sm font-bold mb-1.5" style="color:#374151">الدور *</label><select name="role" required class="w-full rounded-xl px-4 py-3 text-sm font-medium"><option value="viewer">مشاهد</option><option value="operator">مشغل</option><option value="manager">مدير</option></select></div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="flex-1 py-3 rounded-xl text-sm font-extrabold text-white" style="background:#111827;color:#ffffff">إضافة العامل</button>
                        <button type="button" @click="showModal = false" class="px-6 py-3 rounded-xl text-sm font-bold" style="background:#f1f3f8;color:#374151">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
