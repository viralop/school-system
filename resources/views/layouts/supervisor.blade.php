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
        [dir="rtl"] .rtl\:space-x-reverse { space-x-reverse: 1; }
        .sidebar-link.active { background: rgba(59,130,246,0.15); color: var(--text-primary); font-weight: 500; }
        .sidebar-overlay { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }
        @media (max-width: 767px) {
            #sidebar { width: 0; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            #sidebar.active { width: 17rem; }
        }
    </style>
</head>
<body class="min-h-screen">
    @php
        $locale = app()->getLocale();
        $theme = session('theme', 'dark');
        $isRtl = $locale === 'ar';
        $pendingGradesCount = \App\Models\Grade::where('status', 'pending')->count();

        $navLinks = [
            ['route' => 'supervisor.dashboard', 'label' => __('messages.Dashboard'), 'match' => 'supervisor.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>'],
            ['route' => 'supervisor.levels.index', 'label' => __('messages.Levels'), 'match' => 'supervisor.levels.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/>'],
            ['route' => 'supervisor.teachers.index', 'label' => __('messages.Teachers'), 'match' => 'supervisor.teachers.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>'],
            ['route' => 'supervisor.students.index', 'label' => __('messages.Students'), 'match' => 'supervisor.students.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'],
            ['route' => 'supervisor.sections.index', 'label' => __('messages.Sections'), 'match' => 'supervisor.sections.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25"/>'],
            ['route' => 'supervisor.subjects.index', 'label' => __('messages.Subjects'), 'match' => 'supervisor.subjects.*', 'dot' => $pendingGradesCount > 0, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>'],
            ['route' => 'supervisor.terms.index', 'label' => __('messages.Terms'), 'match' => 'supervisor.terms.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>'],
            ['route' => 'supervisor.monthly-exams.index', 'label' => __('messages.Monthly Exams'), 'match' => 'supervisor.monthly-exams.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>'],
            ['route' => 'supervisor.results', 'label' => __('messages.Results'), 'match' => 'supervisor.results', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>'],
            ['route' => 'supervisor.content.edit', 'label' => __('messages.Content'), 'match' => 'supervisor.content.*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>'],
        ];
    @endphp

    <div class="sidebar-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden" id="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="flex min-h-screen overflow-x-hidden">
        <aside class="sidebar-panel w-64 bg-[var(--bg-card)] border-{{ $isRtl ? 'l' : 'r' }} border-[var(--border-main)] flex flex-col fixed top-0 {{ $isRtl ? 'right-0' : 'left-0' }} h-full z-50 md:w-64 w-0 overflow-hidden" id="sidebar">
            <div class="p-5 border-b border-[var(--border-main)]">
                <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="/logo.jpg" alt="خولة بنت الأزور" class="w-12 h-12 rounded-xl object-cover shadow-md">
                            <div>
                            <h1 class="text-lg font-bold text-[var(--text-primary)]">خولة بنت الأزور</h1>
                            <span class="text-blue-400 text-xs">{{ __('messages.Supervisor') }}</span>
                        </div>
                    </div>
                    <button onclick="closeSidebar()" class="md:hidden p-2 text-[var(--text-secondary)] hover:text-[var(--text-primary)] rounded-lg hover:bg-[var(--bg-hover)] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                @foreach($navLinks as $link)
                    @php $isActive = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}" onclick="closeSidebar()"
                        class="sidebar-link relative flex items-center gap-3 text-sm px-3 py-2.5 rounded-xl transition {{ $isActive ? 'active' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-hover)]' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $link['icon'] !!}</svg>
                        {{ $link['label'] }}
                        @if(!empty($link['dot']))
                            <span class="absolute {{ $isRtl ? 'left-2' : 'right-2' }} top-2 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="p-3 border-t border-[var(--border-main)] space-y-2">
                <div class="flex items-center gap-2 px-3">
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
                    <button type="submit" class="w-full text-[var(--text-tertiary)] hover:text-red-400 text-sm flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-red-500/10 transition">
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
                    <img src="/logo.jpg" alt="خولة بنت الأزور" class="w-10 h-10 rounded-lg object-cover">
                    <span class="text-sm font-bold text-[var(--text-primary)]">خولة بنت الأزور</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url()->current() }}?lang={{ $locale === 'ar' ? 'en' : 'ar' }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2 py-1 rounded-lg border border-[var(--border-main)] transition font-medium">{{ $locale === 'ar' ? 'EN' : 'عربي' }}</a>
                </div>
            </header>

            <div class="p-4 md:p-8">
                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl mb-4 md:mb-6 flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4 md:mb-6 flex items-center gap-2 text-sm">
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
            document.querySelector('.sidebar-panel').classList.add('active');
            document.getElementById('sidebar-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.querySelector('.sidebar-panel').classList.remove('active');
            document.getElementById('sidebar-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>
    @stack('scripts')
</body>
</html>