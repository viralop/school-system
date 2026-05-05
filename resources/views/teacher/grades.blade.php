@extends('layouts.teacher')

@section('title', 'My Grades - خولة بنت الأزور')

@section('content')
    <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6">{{ __('messages.My Subjects') }}</h2>

    @if($subjects->count() > 0)
        @foreach($subjects as $subject)
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6 hover:border-[var(--border-hover)] transition shadow-[var(--shadow-card)]">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[var(--text-primary)] font-semibold">{{ $subject->localizedName }}</h3>
                        <p class="text-[var(--text-secondary)] text-sm">{{ $subject->level->localizedName }} | {{ __('messages.Max Score') }}: {{ $subject->default_max_degree }}</p>
                    </div>
                </div>

                <h4 class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-2">{{ __('messages.Term Grades') }}</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                    @foreach($subject->level->terms as $term)
                        @if($term->isOpen())
                            <a href="{{ route('teacher.grades.entry', ['exam_type' => 'term', 'subject_id' => $subject->id, 'exam_id' => $term->id]) }}"
                                class="block bg-[var(--bg-hover)] rounded-lg p-3 hover:bg-[var(--bg-input)] transition border border-[var(--border-main)] no-underline">
                                <p class="text-[var(--text-primary)] text-sm">{{ $term->localizedName }}</p>
                                <p class="text-[var(--text-secondary)] text-xs">{{ __('messages.Max Score') }}: {{ $subject->default_max_degree }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>

                @php
                    $teacherMonthlyExams = $monthlyExams->filter(fn($e) => $e->subjects->contains(fn($s) => $s->subject_id === $subject->id));
                @endphp
                @if($teacherMonthlyExams->count() > 0)
                    <h4 class="text-[var(--text-secondary)] text-xs uppercase tracking-wider mb-2">{{ __('messages.Monthly Exams') }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($teacherMonthlyExams as $exam)
                            <a href="{{ route('teacher.grades.entry', ['exam_type' => 'monthly', 'exam_id' => $exam->id]) }}"
                                class="block bg-purple-500/5 rounded-lg p-3 hover:bg-purple-500/10 transition border border-purple-500/20 no-underline">
                                <div class="flex items-center justify-between">
                                    <p class="text-[var(--text-primary)] text-sm">{{ $exam->localizedName }}</p>
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-500/15 text-purple-400">{{ __('messages.Monthly Exam') }}</span>
                                </div>
                                <p class="text-[var(--text-secondary)] text-xs">
                                    @if($exam->date) {{ $exam->date->format('Y-m-d') }} | @endif
                                    {{ $exam->level->localizedName }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 text-center shadow-[var(--shadow-card)]">
            <svg class="w-12 h-12 text-[var(--text-tertiary)] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            <p class="text-[var(--text-secondary)]">{{ __('messages.No subjects assigned to you yet. Contact the supervisor.') }}</p>
        </div>
    @endif
@endsection
