@extends('layouts.supervisor')

@section('title', 'Supervisor Dashboard - خولة بنت الأزور')

@section('content')
    @php
        $studentCount = \App\Models\Student::count();
        $teacherCount = \App\Models\User::where('role', 'teacher')->count();
        $subjectCount = \App\Models\Subject::count();
        $termCount = \App\Models\Term::distinct()->count('name');
        $pendingGrades = \App\Models\Grade::where('status', 'pending')->count();
    @endphp

    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] md:min-h-[calc(100vh-8rem)] relative">
        <div class="w-full max-w-5xl space-y-4 md:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                <a href="{{ route('supervisor.students.index') }}" class="dashboard-card block bg-[var(--bg-card)] rounded-2xl border border-[var(--border-main)] overflow-hidden no-underline group animate-fade-in-up stagger-1">
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Students') }}</p>
                            <p class="stat-number text-3xl md:text-4xl font-bold text-[var(--text-primary)]">{{ $studentCount }}</p>
                        </div>
                        <div class="card-icon w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-blue-500/15 to-cyan-500/15 flex items-center justify-center">
                            <svg class="w-6 h-6 md:w-7 md:h-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('supervisor.teachers.index') }}" class="dashboard-card block bg-[var(--bg-card)] rounded-2xl border border-[var(--border-main)] overflow-hidden no-underline group animate-fade-in-up stagger-2">
                    <div class="h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Teachers') }}</p>
                            <p class="stat-number text-3xl md:text-4xl font-bold text-[var(--text-primary)]">{{ $teacherCount }}</p>
                        </div>
                        <div class="card-icon w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-500/15 to-teal-500/15 flex items-center justify-center">
                            <svg class="w-6 h-6 md:w-7 md:h-7 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.375 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('supervisor.subjects.index') }}" class="dashboard-card block bg-[var(--bg-card)] rounded-2xl border border-[var(--border-main)] overflow-hidden no-underline group animate-fade-in-up stagger-3">
                    <div class="h-1.5 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Subjects') }}</p>
                            <p class="stat-number text-3xl md:text-4xl font-bold text-[var(--text-primary)]">{{ $subjectCount }}</p>
                        </div>
                        <div class="card-icon w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-purple-500/15 to-pink-500/15 flex items-center justify-center">
                            <svg class="w-6 h-6 md:w-7 md:h-7 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                <a href="{{ route('supervisor.terms.index') }}" class="dashboard-card block bg-[var(--bg-card)] rounded-2xl border border-[var(--border-main)] overflow-hidden no-underline group animate-fade-in-up stagger-4">
                    <div class="h-1.5 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Terms') }}</p>
                            <p class="stat-number text-3xl md:text-4xl font-bold text-[var(--text-primary)]">{{ $termCount }}</p>
                        </div>
                        <div class="card-icon w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-amber-500/15 to-orange-500/15 flex items-center justify-center">
                            <svg class="w-6 h-6 md:w-7 md:h-7 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('supervisor.monthly-exams.index') }}" class="dashboard-card block bg-[var(--bg-card)] rounded-2xl border border-[var(--border-main)] overflow-hidden no-underline group animate-fade-in-up stagger-5">
                    @php $examCount = \App\Models\MonthlyExam::count(); @endphp
                    <div class="h-1.5 bg-gradient-to-r from-cyan-500 to-blue-500"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Monthly Exams') }}</p>
                            <p class="stat-number text-3xl md:text-4xl font-bold text-[var(--text-primary)]">{{ $examCount }}</p>
                        </div>
                        <div class="card-icon w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-cyan-500/15 to-blue-500/15 flex items-center justify-center">
                            <svg class="w-6 h-6 md:w-7 md:h-7 text-cyan-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('supervisor.grades.pending') }}" class="dashboard-card block bg-[var(--bg-card)] rounded-2xl border border-[var(--border-main)] overflow-hidden no-underline group animate-fade-in-up stagger-6">
                    <div class="h-1.5 bg-gradient-to-r from-red-500 to-rose-500"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Pending Grades') }}</p>
                            <p class="stat-number text-3xl md:text-4xl font-bold text-red-500">{{ $pendingGrades }}</p>
                        </div>
                        <div class="card-icon w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-red-500/15 to-rose-500/15 flex items-center justify-center">
                            <svg class="w-6 h-6 md:w-7 md:h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
