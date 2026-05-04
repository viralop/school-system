<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.Student Dashboard') - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="min-h-screen">
    @php
        $locale = app()->getLocale();
        $theme = session('theme', 'dark');
        $student = \App\Models\Student::with(['level', 'section'])->find(session('student_id'));
        $grades = \App\Models\Grade::where('student_id', $student->id)
            ->where('status', 'approved')
            ->with(['subject', 'term'])
            ->get()
            ->groupBy('term_id');
        $levelGrade = app(\App\Services\GradeCalculationService::class)
            ->calculateLevelGrade($student->id, $student->level_id);
    @endphp

    <nav class="bg-[var(--bg-card-80)] border-b border-[var(--border-main)] backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    </div>
                    <h1 class="text-lg font-bold text-[var(--text-primary)]">ALWEFAQ <span class="text-purple-400 text-xs font-normal">@lang('messages.Student')</span></h1>
                </div>
                <span class="text-[var(--text-muted)] text-xs sm:text-sm hidden sm:inline">{{ $student->name }} ({{ $student->student_number }})</span>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ url()->current() }}?theme={{ $theme === 'dark' ? 'light' : 'dark' }}"
                   class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2 py-1 rounded-lg border border-[var(--border-main)] hover:border-[var(--border-hover)] transition" title="{{ $theme === 'dark' ? 'Light Mode' : 'Dark Mode' }}">
                    @if($theme === 'dark')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    @endif
                </a>
                <a href="{{ url()->current() }}?lang={{ $locale === 'ar' ? 'en' : 'ar' }}"
                   class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs border border-[var(--border-main)] rounded-lg px-2.5 py-1 transition">
                    {{ $locale === 'ar' ? 'EN' : 'عربي' }}
                </a>
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="text-[var(--text-tertiary)] hover:text-[var(--text-primary)] text-sm flex items-center gap-1.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                        @lang('messages.Logout')
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 sm:py-8">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6 shadow-[var(--shadow-card)]">
            <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">@lang('messages.My Profile')</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                <div>
                    <p class="text-[var(--text-secondary)] text-sm">@lang('messages.Student Number')</p>
                    <p class="text-[var(--text-primary)]">{{ $student->student_number }}</p>
                </div>
                <div>
                    <p class="text-[var(--text-secondary)] text-sm">@lang('messages.Name')</p>
                    <p class="text-[var(--text-primary)]">{{ $student->name }}</p>
                </div>
                <div>
                    <p class="text-[var(--text-secondary)] text-sm">@lang('messages.Level')</p>
                    <p class="text-[var(--text-primary)]">{{ $student->level->localizedName }}</p>
                </div>
                <div>
                    <p class="text-[var(--text-secondary)] text-sm">@lang('messages.Section')</p>
                    <p class="text-[var(--text-primary)]">{{ $student->section->name }}</p>
                </div>
                <div>
                    <p class="text-[var(--text-secondary)] text-sm">@lang('messages.Parent Phone')</p>
                    <p class="text-[var(--text-primary)]">{{ $student->parent_phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[var(--text-secondary)] text-sm">@lang('messages.Status')</p>
                    <span class="px-2 py-1 rounded text-xs font-medium bg-emerald-500/15 text-emerald-400">@lang('messages.Active')</span>
                </div>
            </div>
        </div>

        @if($levelGrade)
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6 shadow-[var(--shadow-card)]">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">@lang('messages.Overall Result') - {{ $student->level->localizedName }}</h2>
                <div class="flex items-center gap-4 sm:gap-8">
                    <div class="text-center">
                        <p class="text-4xl font-bold {{ $levelGrade['passed'] ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $levelGrade['level_percentage'] }}%
                        </p>
                        <p class="text-[var(--text-secondary)] text-sm mt-1">@lang('messages.Level Grade')</p>
                    </div>
                    <div>
                        <span class="px-3 py-1.5 rounded text-sm font-medium
                            {{ $levelGrade['passed'] ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400' }}">
                            {{ $levelGrade['passed'] ? __('messages.PASS') : __('messages.FAIL') }}
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if($grades->count() > 0)
            @foreach($grades as $termId => $termGrades)
                <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6 shadow-[var(--shadow-card)]">
                    <h3 class="text-[var(--text-primary)] font-semibold mb-3">{{ $termGrades->first()->term->localizedName }}</h3>
                    <div class="mobile-scroll-table overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-[var(--text-secondary)] text-sm border-b border-[var(--border-main)]">
                                    <th class="text-left pb-2">@lang('messages.Subject')</th>
                                    <th class="text-center pb-2">@lang('messages.Score')</th>
                                    <th class="text-center pb-2">@lang('messages.Max')</th>
                                    <th class="text-center pb-2">@lang('messages.Percentage')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($termGrades as $grade)
                                    <tr class="border-b border-[var(--border-main)]" style="--tw-border-opacity: 0.3">
                                        <td class="py-2 text-[var(--text-primary)] text-sm">{{ $grade->subject->localizedName }}</td>
                                        <td class="py-2 text-[var(--text-primary)] text-sm text-center">{{ $grade->score }}</td>
                                        <td class="py-2 text-[var(--text-secondary)] text-sm text-center">{{ $grade->subject->max_score }}</td>
                                        <td class="py-2 text-[var(--text-primary)] text-sm text-center font-medium">
                                            {{ round(($grade->score / $grade->subject->max_score) * 100, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 text-center shadow-[var(--shadow-card)]">
                <svg class="w-12 h-12 text-[var(--text-tertiary)] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-2">@lang('messages.My Grades')</h2>
                <p class="text-[var(--text-secondary)] text-sm">@lang('messages.No grades published yet.')</p>
            </div>
        @endif
    </div>
</body>
</html>
