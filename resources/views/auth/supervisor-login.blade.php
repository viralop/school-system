<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Supervisor Login') - خولة بنت الأزور</title>
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
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Supervisor Login')</p>
        </div>

        <form method="POST" action="{{ route('supervisor.login.post') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-[var(--text-label)] text-sm font-medium mb-2">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                    placeholder="supervisor@example.com"
                    required autofocus>
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-[var(--text-label)] text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 placeholder-[var(--text-placeholder)]"
                    placeholder="Enter your password"
                    required>
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-semibold py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] btn-shine">
                @lang('messages.Login')
            </button>
        </form>

        <div class="mt-6 flex items-center justify-center gap-2">
            <a href="{{ route('login') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm transition">
                <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                @lang('messages.Back to Login')
            </a>
        </div>
    </div>
</body>
</html>
