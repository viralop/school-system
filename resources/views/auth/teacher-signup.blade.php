<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Teacher Registration') - خولة بنت الأزور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>body { background: var(--glass-gradient); } .glass { backdrop-filter: blur(16px); }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php $theme = session('theme', 'dark'); $locale = app()->getLocale(); $isRtl = $locale === 'ar'; @endphp
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="/logo.jpg" alt="خولة بنت الأزور" class="mx-auto mb-4 w-20 h-20 rounded-2xl object-cover shadow-lg shadow-blue-500/25">
            <h1 class="text-3xl font-bold text-[var(--text-primary)]">خولة بنت الأزور</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Teacher Registration')</p>
        </div>

        <form method="POST" action="{{ route('teacher.signup.step1') }}">
            @csrf
            <div class="mb-6">
                <label for="teacher_id" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Teacher ID')</label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[var(--text-icon)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                    </div>
                    <input type="text" name="teacher_id" id="teacher_id" value="{{ old('teacher_id') }}"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl {{ $isRtl ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                        required autofocus>
                </div>
                @error('teacher_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-[var(--text-tertiary)] text-xs mt-2">@lang('messages.Enter the teacher ID provided by the supervisor.')</p>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2.5 rounded-xl transition shadow-lg shadow-blue-600/20">
                @lang('messages.Continue')
            </button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm transition">&larr; @lang('messages.Back to Login')</a>
        </div>
        <div class="mt-3 flex items-center justify-between">
            <a href="{{ route('teacher.login') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm transition">@lang('messages.Already have an account? Login')</a>
            <div class="flex items-center gap-2">
                <a href="{{ url()->current() }}?lang={{ $locale === 'ar' ? 'en' : 'ar' }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs border border-[var(--border-main)] rounded-lg px-2.5 py-1 transition">{{ $locale === 'ar' ? 'EN' : 'عربي' }}</a>
                <a href="{{ url()->current() }}?theme={{ $theme === 'dark' ? 'light' : 'dark' }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2 py-1 rounded-lg border border-[var(--border-main)] transition" title="{{ $theme === 'dark' ? 'Light Mode' : 'Dark Mode' }}">
                    @if($theme === 'dark')<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    @else<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    @endif
                </a>
            </div>
        </div>
    </div>
</body>
</html>
