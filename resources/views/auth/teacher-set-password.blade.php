<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Create Account') - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>body { background: var(--glass-gradient); } .glass { backdrop-filter: blur(16px); }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php $theme = session('theme', 'dark'); $locale = app()->getLocale(); @endphp
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="mx-auto mb-4 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/25">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-[var(--text-primary)]">ALWEFAQ</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Set up your account')</p>
            <p class="text-[var(--text-tertiary)] text-xs mt-1">{{ $email }}</p>
        </div>

        <form method="POST" action="{{ route('teacher.signup.password') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Full Name')</label>
                <input type="text" name="name" id="name" value="{{ old('name', $prefilled_name ?? '') }}"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]" required autofocus>
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Password')</label>
                <input type="password" name="password" id="password"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]" required>
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Confirm Password')</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]" required>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2.5 rounded-xl transition shadow-lg shadow-blue-600/20">
                @lang('messages.Create Account')
            </button>
        </form>
    </div>
</body>
</html>
