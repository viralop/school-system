<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Student Login') - خولة بنت الأزور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body { background: var(--glass-gradient); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php $theme = session('theme', 'dark'); @endphp
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8 animate-scale-in">
        <div class="text-center mb-8">
            <div class="animate-float inline-block mb-4 rounded-2xl p-1 bg-gradient-to-br from-blue-500/40 via-purple-500/30 to-pink-500/30">
                <img src="/logo.jpg" alt="خولة بنت الأزور" class="w-20 h-20 rounded-2xl object-cover shadow-lg shadow-blue-500/25">
            </div>
            <h1 class="text-3xl font-bold gradient-text">خولة بنت الأزور</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Student Portal')</p>
            <p class="text-[var(--text-secondary)] text-xs mt-2">@lang('messages.Enter your student number to access your grades and profile.')</p>
        </div>

        <form method="POST" action="{{ route('student.login') }}">
            @csrf

            <div class="mb-6">
                <label for="student_number" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Student Number')</label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[var(--text-icon)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    </div>
                    <input type="text" name="student_number" id="student_number"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                        placeholder="@lang('messages.e.g. 2026001')"
                        required autofocus>
                </div>
                @error('student_number')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] btn-shine">
                @lang('messages.Access My Profile')
            </button>
        </form>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm transition">
                <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                @lang('messages.Back to Login')
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ url()->current() }}?theme={{ $theme === 'dark' ? 'light' : 'dark' }}"
                   class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2 py-1 rounded-lg border border-[var(--border-main)] transition"
                   title="{{ $theme === 'dark' ? 'Light Mode' : 'Dark Mode' }}">
                    @if($theme === 'dark')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    @endif
                </a>
                <a href="{{ url()->current() }}?lang={{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}"
                   class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs border border-[var(--border-main)] rounded-lg px-2.5 py-1 transition">
                    {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>
