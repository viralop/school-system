<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $theme = session('theme', 'dark');
    $schoolName = \App\Models\SiteSetting::get('school_name_' . $locale, 'ALWEFAQ');
    $otherLocale = $locale === 'en' ? 'ar' : 'en';
    $otherLabel = $locale === 'en' ? 'العربية' : 'English';
@endphp
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ALWEFAQ')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    @if($locale === 'ar')
        <style>body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; }</style>
    @endif
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    <nav id="visitor-nav" class="sticky top-0 z-50" style="transition: background-color 0.3s ease, backdrop-filter 0.3s ease, -webkit-backdrop-filter 0.3s ease;">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    </div>
                    <span class="text-xl font-bold text-white">{{ $schoolName }}</span>
                </a>
                <div class="flex items-center gap-5">
                    <a href="{{ route('home') }}" class="text-white/70 hover:text-white text-sm transition @yield('nav-home-active')">@lang('messages.Home')</a>
                    <a href="{{ route('about') }}" class="text-white/70 hover:text-white text-sm transition @yield('nav-about-active')">@lang('messages.About')</a>
                    <a href="{{ route('achievements') }}" class="text-white/70 hover:text-white text-sm transition @yield('nav-achievements-active')">@lang('messages.Achievements')</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm text-blue-300 hover:text-blue-200 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    @lang('messages.Login')
                </a>
                <a href="{{ url()->current() }}?theme={{ $theme === 'dark' ? 'light' : 'dark' }}"
                   class="text-white/70 hover:text-white text-xs px-2 py-1 rounded-lg border border-white/20 transition"
                   title="{{ $theme === 'dark' ? 'Light Mode' : 'Dark Mode' }}">
                    @if($theme === 'dark')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    @endif
                </a>
                <a href="{{ url()->current() }}?lang={{ $otherLocale }}" class="text-blue-300 hover:text-blue-200 text-sm font-medium bg-white/10 px-3 py-1 rounded-lg transition">{{ $otherLabel }}</a>
            </div>
        </div>
    </nav>

    <main class="flex-1 relative">
        <div class="fixed inset-0 -z-10">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 {{ $theme === 'dark' ? 'bg-black/60' : 'bg-black/40' }}"></div>
        </div>

        @yield('content')
    </main>

    <footer class="bg-black/40 backdrop-blur-xl border-t border-white/10 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-2 mb-3">
                <div class="w-6 h-6 rounded-lg bg-blue-600/20 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                </div>
                <span class="text-white font-semibold">{{ $schoolName }}</span>
            </div>
            <p class="text-white/50 text-sm">&copy; {{ date('Y') }} {{ $schoolName }}. @lang('messages.All rights reserved').</p>
        </div>
    </footer>
    <script>
        const nav = document.getElementById('visitor-nav');
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const progress = Math.min(window.scrollY / 120, 1);
                    const opacity = (progress * 0.7).toFixed(3);
                    const blur = Math.round(progress * 20);
                    nav.style.backgroundColor = `rgba(0,0,0,${opacity})`;
                    nav.style.backdropFilter = `blur(${blur}px)`;
                    nav.style.webkitBackdropFilter = `blur(${blur}px)`;
                    ticking = false;
                });
                ticking = true;
            }
        });
    </script>
</body>
</html>
