@extends('layouts.teacher')

@section('title', 'My Grades - ALWEFAQ')

@section('content')
    <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6">{{ __('messages.My Subjects') }}</h2>

    @if($subjects->count() > 0)
        @foreach($subjects as $subject)
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6 hover:border-[var(--border-hover)] transition shadow-[var(--shadow-card)]">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[var(--text-primary)] font-semibold">{{ $subject->name }}</h3>
                        <p class="text-[var(--text-secondary)] text-sm">{{ $subject->level->name }} | {{ __('messages.Max Score') }}: {{ $subject->max_score }}</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('teacher.grades.entry') }}" class="flex gap-3 items-end">
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Select Term') }}</label>
                        <select name="term_id" class="bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                            <option value="">{{ __('messages.Choose term...') }}</option>
                            @foreach($subject->level->terms as $term)
                                <option value="{{ $term->id }}" class="{{ $term->isOpen() ? '' : 'text-gray-500' }}">
                                    {{ $term->name }} {{ $term->isOpen() ? '(Open)' : '(' . ucfirst($term->status) . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">
                        {{ __('messages.Enter Grades') }}
                    </button>
                </form>
            </div>
        @endforeach
    @else
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 text-center shadow-[var(--shadow-card)]">
            <svg class="w-12 h-12 text-[var(--text-tertiary)] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            <p class="text-[var(--text-secondary)]">{{ __('messages.No subjects assigned to you yet. Contact the supervisor.') }}</p>
        </div>
    @endif
@endsection
