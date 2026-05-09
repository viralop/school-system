<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0a0f">
    <title>@yield('title', 'خولة بنت الأزور')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        .sidebar-link.active { background: transparent; color: var(--text-primary); font-weight: 500; }
        .sidebar-overlay { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }
        .sidebar-nav { scrollbar-width: none; -ms-overflow-style: none; }
        .sidebar-nav::-webkit-scrollbar { display: none; }
        @media (max-width: 767px) {
            #sidebar { width: 0; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            #sidebar.active { width: 17rem; }
        }
        .sidebar-link svg { transition: transform 0.2s ease; }
        .sidebar-link:hover svg { transform: scale(1.1); }
        .sidebar-panel { transition: box-shadow 0.3s ease; }
        .sidebar-panel:hover { box-shadow: 4px 0 24px -4px rgba(52, 211, 153, 0.08); }
        .logo-pulse { animation: pulseGlow 4s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen">
    @php
        $locale = app()->getLocale();
        $theme = session('theme', 'dark');
        $isRtl = $locale === 'ar';
        $teacher = auth()->user();

        $navLinks = [
            ['route' => 'teacher.dashboard', 'label' => __('messages.Dashboard'), 'match' => 'teacher.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>'],
            ['route' => 'teacher.grades', 'label' => __('messages.Grades'), 'match' => 'teacher.grades.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>'],
        ];
    @endphp

    <div class="sidebar-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden" id="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="flex min-h-screen overflow-x-hidden">
        <aside class="sidebar-panel w-64 bg-[var(--bg-card)] border-{{ $isRtl ? 'l' : 'r' }} border-[var(--border-main)] flex flex-col fixed top-0 {{ $isRtl ? 'right-0' : 'left-0' }} h-full z-50 md:w-64 w-0 overflow-hidden" id="sidebar">
            <div class="p-5 border-b border-[var(--border-main)]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="logo-pulse rounded-xl p-0.5 bg-gradient-to-br from-emerald-500/40 via-teal-500/30 to-blue-500/30">
                            <img src="/logo.jpg" alt="خولة بنت الأزور" class="w-12 h-12 rounded-xl object-cover shadow-lg">
                        </div>
                        <div>
                            <h1 class="text-lg font-bold gradient-text">خولة بنت الأزور</h1>
                            <span class="text-xs font-medium bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">{{ __('messages.Teacher') }}</span>
                        </div>
                    </div>
                    <button onclick="closeSidebar()" class="md:hidden p-2 text-[var(--text-secondary)] hover:text-[var(--text-primary)] rounded-lg hover:bg-[var(--bg-hover)] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <nav class="sidebar-nav flex-1 p-3 space-y-1">
                @foreach($navLinks as $link)
                    @php $isActive = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}" onclick="closeSidebar()"
                        class="sidebar-link relative flex items-center gap-3 text-sm px-3 py-2.5 rounded-xl transition {{ $isActive ? 'active' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-hover)]' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $link['icon'] !!}</svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="p-4 border-t border-[var(--border-main)]">
                <div class="flex items-center gap-3 mb-3 px-1">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[var(--text-primary)] text-sm font-medium truncate">{{ $teacher->name }}</p>
                        <p class="text-[var(--text-secondary)] text-xs truncate">{{ $teacher->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-1 mb-2">
                    <a href="{{ url()->current() }}?theme={{ $theme === 'dark' ? 'light' : 'dark' }}"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-2 py-1.5 rounded-lg border border-[var(--border-main)] hover:border-[var(--border-hover)] transition"
                        title="{{ $theme === 'dark' ? 'Light Mode' : 'Dark Mode' }}">
                        @if($theme === 'dark')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                        @endif
                    </a>
                    <a href="{{ url()->current() }}?lang={{ $locale === 'ar' ? 'en' : 'ar' }}"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2.5 py-1.5 rounded-lg border border-[var(--border-main)] hover:border-[var(--border-hover)] transition font-medium">
                        {{ $locale === 'ar' ? 'EN' : 'عربي' }}
                    </a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-[var(--text-tertiary)] hover:text-red-400 text-sm flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-red-500/10 transition-all duration-200 hover:translate-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                        {{ __('messages.Logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 min-w-0 {{ $isRtl ? 'md:mr-64' : 'md:ml-64' }}">
            <header class="md:hidden sticky top-0 z-30 bg-[var(--bg-card)] border-b border-[var(--border-main)] px-4 py-3 flex items-center justify-between">
                <button onclick="openSidebar()" class="p-2 -ml-2 text-[var(--text-secondary)] hover:text-[var(--text-primary)] rounded-lg hover:bg-[var(--bg-hover)] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="rounded-lg p-0.5 bg-gradient-to-br from-emerald-500/30 via-teal-500/20 to-blue-500/20">
                        <img src="/logo.jpg" alt="خولة بنت الأزور" class="w-10 h-10 rounded-lg object-cover">
                    </div>
                    <span class="text-sm font-bold gradient-text">خولة بنت الأزور</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url()->current() }}?lang={{ $locale === 'ar' ? 'en' : 'ar' }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2 py-1 rounded-lg border border-[var(--border-main)] transition font-medium">{{ $locale === 'ar' ? 'EN' : 'عربي' }}</a>
                </div>
            </header>

            <div class="p-4 md:p-8">
                @if(session('success'))
                    <div class="animate-slide-in-right bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl mb-4 md:mb-6 flex items-center gap-2 text-sm hover-glow">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="animate-slide-in-right bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4 md:mb-6 flex items-center gap-2 text-sm hover-glow">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('active');
            document.getElementById('sidebar-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebar-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.animate-slide-in-right').forEach(function(el) {
                setTimeout(function() {
                    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(20px)';
                    setTimeout(function() { el.remove(); }, 500);
                }, 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>