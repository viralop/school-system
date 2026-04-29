<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Verify Code') - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>body { background: var(--glass-gradient); } .glass { backdrop-filter: blur(16px); }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="glass rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="mx-auto mb-4 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/25">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a48.667 48.667 0 0 0-1.486 8.354M6.09 14.584A48.32 48.32 0 0 1 12 10.5c2.29 0 4.512.304 6.623.87"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-[var(--text-primary)]">ALWEFAQ</h1>
            <p class="text-[var(--text-muted)] mt-1 text-sm">@lang('messages.Check your email')</p>
            <p class="text-[var(--text-tertiary)] text-xs mt-1">@lang('messages.We sent a 6-digit code to') <span class="text-[var(--text-label)]">{{ $email }}</span></p>
        </div>

        @if(session('status'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.signup.verify-otp') }}">
            @csrf
            <div class="mb-6">
                <input type="text" name="code" id="code" maxlength="6"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-xl px-4 py-3 text-center text-2xl tracking-[0.5em] focus:ring-2 focus:ring-blue-500/40 outline-none placeholder-[var(--text-placeholder)]"
                    placeholder="000000" required autofocus inputmode="numeric" pattern="[0-9]{6}">
                @error('code')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2.5 rounded-xl transition shadow-lg shadow-blue-600/20">
                @lang('messages.Verify Code')
            </button>
        </form>

        <div class="text-center mt-6">
            <form method="POST" action="{{ route('teacher.resend-otp') }}">
                @csrf
                <input type="hidden" name="purpose" value="teacher_signup">
                <button type="submit" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm transition">@lang('messages.Resend Code')</button>
            </form>
        </div>
    </div>
</body>
</html>
