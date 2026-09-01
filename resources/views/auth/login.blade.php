@extends('layouts.guest')

@section('content')
<div class="glow rounded-2xl bg-dark-900/80 backdrop-blur-xl border border-dark-700/50 p-8">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-purple-600 mb-4 float-animation">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold">
            <span class="bg-gradient-to-l from-primary-400 to-purple-400 bg-clip-text text-transparent">DZCommerce</span>
        </h1>
        <p class="text-dark-400 mt-2 text-sm">منصة التجارة الإلكترونية السحابية</p>
    </div>

    <h2 class="text-xl font-bold text-dark-100 text-center mb-6">تسجيل الدخول</h2>

    @if ($errors->any())
    <div class="mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/30">
        @foreach ($errors->all() as $error)
            <p class="text-red-400 text-sm">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    @if (session('success'))
    <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
        <p class="text-emerald-400 text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-dark-300 mb-2">البريد الإلكتروني</label>
            <div class="relative">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    dir="ltr"
                    class="w-full pr-10 pl-4 py-3 bg-dark-800/50 border border-dark-600/50 rounded-xl text-dark-100 placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50 transition-all duration-300 text-left"
                    placeholder="name@example.com"
                    required
                    autofocus
                >
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-dark-300 mb-2">كلمة المرور</label>
            <div class="relative">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    dir="ltr"
                    class="w-full pr-10 pl-12 py-3 bg-dark-800/50 border border-dark-600/50 rounded-xl text-dark-100 placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50 transition-all duration-300 text-left"
                    placeholder="••••••••"
                    required
                >
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 left-0 pl-3 flex items-center text-dark-500 hover:text-dark-300 transition-colors">
                    <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-primary-500 focus:ring-primary-500/50 focus:ring-offset-0 cursor-pointer"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <span class="text-sm text-dark-400 group-hover:text-dark-300 transition-colors">تذكرني</span>
            </label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-primary-400 hover:text-primary-300 transition-colors">
                نسيت كلمة المرور؟
            </a>
            @endif
        </div>

        <button
            type="submit"
            class="w-full py-3 px-4 bg-gradient-to-l from-primary-600 to-purple-600 hover:from-primary-500 hover:to-purple-500 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary-500/50 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40"
        >
            دخول
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-dark-400 text-sm">
            ليس لديك حساب؟
            <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-semibold transition-colors">إنشاء حساب جديد</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    const eyeOffIcon = document.getElementById('eye-off-icon');
    if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        password.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}
</script>
@endpush
