<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Login') - خولة بنت الأزور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body { background: var(--glass-gradient); }
        .glass { backdrop-filter: blur(16px); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php $theme = session('theme', 'dark'); $locale = app()->getLocale(); $isRtl = $locale === 'ar'; @endphp
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8 animate-scale-in">
        <div class="text-center mb-8">
            <div class="animate-float inline-block mb-4 rounded-2xl p-1 bg-gradient-to-br from-blue-500/40 via-purple-500/30 to-pink-500/30">
                <img src="/logo.jpg" alt="خولة بنت الأزور" class="w-20 h-20 rounded-2xl object-cover shadow-lg shadow-blue-500/25">
            </div>
            <h1 class="text-3xl font-bold gradient-text">خولة بنت الأزور</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.School Management System')</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Email Address')</label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[var(--text-icon)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl {{ $isRtl ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                        required autofocus>
                </div>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label for="password" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Password')</label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[var(--text-icon)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl {{ $isRtl ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                        required>
                </div>
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-semibold py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] btn-shine">
                    @lang('messages.Login')
                </button>
                <a href="{{ route('register') }}" class="flex-1 text-center bg-[var(--bg-hover)] hover:bg-[var(--border-hover)] border border-[var(--border-input)] text-[var(--text-primary)] font-semibold py-2.5 rounded-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                    @lang('messages.Sign Up')
                </a>
            </div>
        </form>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[var(--divider-line)]"></div></div>
            <div class="relative flex justify-center"><span class="bg-[var(--divider-bg)] px-3 text-[var(--divider-text)] text-xs">@lang('messages.PORTALS')</span></div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('teacher.login') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-emerald-600/80 to-teal-600/80 hover:from-emerald-500/80 hover:to-teal-500/80 text-white font-medium py-2.5 rounded-xl transition-all duration-200 text-sm hover:scale-[1.02] active:scale-[0.98] hover:shadow-lg hover:shadow-emerald-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                @lang('messages.Teacher Login')
            </a>
            <a href="{{ route('student.login') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-purple-600/60 to-pink-600/60 hover:from-purple-500/60 hover:to-pink-500/60 text-white font-medium py-2.5 rounded-xl transition-all duration-200 text-sm hover:scale-[1.02] active:scale-[0.98] hover:shadow-lg hover:shadow-purple-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                @lang('messages.Student Login')
            </a>
        </div>

        <div class="mt-4 flex justify-center">
            <a href="{{ url()->current() }}?lang={{ $locale === 'ar' ? 'en' : 'ar' }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs border border-[var(--border-main)] rounded-lg px-2.5 py-1 transition">
                {{ $locale === 'ar' ? 'EN' : 'عربي' }}
            </a>
        </div>
    </div>
</body>
</html>
