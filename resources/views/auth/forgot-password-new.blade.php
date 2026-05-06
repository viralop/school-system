<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Set New Password') - خولة بنت الأزور</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>body { background: var(--glass-gradient); } .glass { backdrop-filter: blur(16px); }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    @php $theme = session('theme', 'dark'); $locale = app()->getLocale(); @endphp
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="/logo.jpg" alt="خولة بنت الأزور" class="mx-auto mb-4 w-20 h-20 rounded-2xl object-cover shadow-lg shadow-blue-500/25">
            <h1 class="text-3xl font-bold text-[var(--text-primary)]">خولة بنت الأزور</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Set New Password')</p>
        </div>

        <form method="POST" action="{{ route('password.reset.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.New Password')</label>
                <input type="password" name="password"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]" required autofocus>
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label class="block text-[var(--text-label)] text-sm font-medium mb-2">@lang('messages.Confirm Password')</label>
                <input type="password" name="password_confirmation"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]" required>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2.5 rounded-xl transition shadow-lg shadow-blue-600/20">@lang('messages.Reset Password')</button>
        </form>
    </div>
</body>
</html>
