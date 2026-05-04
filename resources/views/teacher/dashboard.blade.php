@extends('layouts.teacher')

@section('title', 'Teacher Dashboard - ALWEFAQ')

@section('content')
    @php
        $teacher = auth()->user();
        $subjects = \App\Models\Subject::where('teacher_id', $teacher->id)->with('level.terms')->get();
        $studentCount = \App\Models\Student::whereIn('level_id', $subjects->pluck('level_id')->unique())->count();
        $pendingGrades = \App\Models\Grade::where('entered_by', $teacher->id)->where('status', 'pending')->count();
        $approvedGrades = \App\Models\Grade::where('entered_by', $teacher->id)->where('status', 'approved')->count();
    @endphp

    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] md:min-h-[calc(100vh-8rem)]">
        <div class="w-full max-w-3xl space-y-6">
            <div class="grid grid-cols-2 gap-3 md:gap-6">
                <a href="{{ route('teacher.grades') }}" class="block bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] overflow-hidden hover:shadow-lg transition-all duration-300 no-underline group">
                    <div class="h-2 bg-gradient-to-r from-purple-500 to-purple-600"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.My Subjects') }}</p>
                            <p class="text-4xl font-bold text-[var(--text-primary)]">{{ $subjects->count() }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 flex items-center justify-center transition">
                            <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('teacher.grades') }}" class="block bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] overflow-hidden hover:shadow-lg transition-all duration-300 no-underline group">
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Students') }}</p>
                            <p class="text-4xl font-bold text-[var(--text-primary)]">{{ $studentCount }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center transition">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('teacher.grades') }}" class="block bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] overflow-hidden hover:shadow-lg transition-all duration-300 no-underline group">
                    <div class="h-2 bg-gradient-to-r from-yellow-500 to-amber-600"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Pending Grades') }}</p>
                            <p class="text-4xl font-bold text-yellow-400">{{ $pendingGrades }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-yellow-500/10 group-hover:bg-yellow-500/20 flex items-center justify-center transition">
                            <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                    </div>
                </a>
                <a href="{{ route('teacher.grades') }}" class="block bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] overflow-hidden hover:shadow-lg transition-all duration-300 no-underline group">
                    <div class="h-2 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="p-4 md:p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-1">{{ __('messages.Approve') }}</p>
                            <p class="text-4xl font-bold text-emerald-400">{{ $approvedGrades }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-emerald-500/10 group-hover:bg-emerald-500/20 flex items-center justify-center transition">
                            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @if($subjects->count() > 0)
        <div class="mt-4 md:mt-8">
            <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.My Subjects') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($subjects as $subject)
                    @php
                        $openTerms = $subject->level->terms->filter(fn($t) => $t->isOpen());
                        $url = $openTerms->count() === 1
                            ? route('teacher.grades.entry', ['subject_id' => $subject->id, 'term_id' => $openTerms->first()->id])
                            : route('teacher.grades');
                    @endphp
                    <a href="{{ $url }}" class="block bg-[var(--bg-card)] p-5 rounded-xl border border-[var(--border-main)] hover:border-[var(--border-hover)] transition shadow-[var(--shadow-card)] no-underline">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-lg bg-purple-500/15 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                            </div>
                            <p class="text-[var(--text-primary)] font-medium">{{ $subject->localizedName }}</p>
                        </div>
                        <p class="text-[var(--text-secondary)] text-sm ml-0 md:ml-12">{{ $subject->level->localizedName }} | {{ __('messages.Max Score') }}: {{ $subject->max_score }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endsection
