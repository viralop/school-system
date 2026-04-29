<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Sign Up') - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>body { background: var(--glass-gradient); } .glass { backdrop-filter: blur(16px); }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php $theme = session('theme', 'dark'); $locale = app()->getLocale(); @endphp
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="mx-auto mb-4 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/25">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-[var(--text-primary)]">ALWEFAQ</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Create Account')</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Full Name')</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                    required autofocus>
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Email Address')</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                    required>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Password')</label>
                <input type="password" name="password" id="password"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                    required>
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Confirm Password')</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                    required>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2.5 rounded-xl transition shadow-lg shadow-blue-600/20">
                @lang('messages.Sign Up')
            </button>
        </form>
        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm">@lang('messages.Already have an account? Login')</a>
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
