@extends('layouts.supervisor')

@section('title', '{{ $subject->name }} Grades - ALWEFAQ')

@section('content')
    <div class="mb-6">
        <a href="{{ route('supervisor.subjects.index') }}" class="text-blue-400/60 hover:text-blue-300 text-sm flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            {{ __('messages.Back to Subjects') }}
        </a>
    </div>

    <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)]">{{ $subject->name }}</h2>
                <p class="text-[var(--text-secondary)] text-sm">{{ $subject->level->name }} | {{ __('messages.Max Score') }}: {{ $subject->max_score }} | {{ __('messages.Teacher') }}: {{ $subject->teacher ? $subject->teacher->name : 'None' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @php $pendingCount = $subject->grades()->where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="text-red-400 text-sm font-medium">{{ $pendingCount }} {{ __('messages.pending') }}</span>
                    <form method="POST" action="{{ route('supervisor.grades.bulk-approve') }}" class="inline">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <button type="submit" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-4 py-2 rounded-lg text-sm transition">{{ __('messages.Approve All') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
        <h3 class="text-[var(--text-primary)] font-semibold mb-3">{{ __('messages.Import Grades from File') }}</h3>
        <p class="text-[var(--text-secondary)] text-xs mb-3">CSV format: <code class="bg-[var(--bg-input)] px-1.5 py-0.5 rounded">student_number, score</code></p>
        <form method="POST" action="{{ route('supervisor.grades.import') }}" enctype="multipart/form-data" class="flex gap-3 items-end flex-wrap">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
            <div class="min-w-[150px]">
                <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Term') }}</label>
                <select name="term_id"
                    class="bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                    @foreach($terms as $term)
                        <option value="{{ $term->id }}" {{ !$term->isOpen() ? 'disabled' : '' }}>{{ $term->name }} {{ $term->isOpen() ? '(Open)' : '(Closed)' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[var(--text-secondary)] text-sm mb-1">File (CSV, XLSX)</label>
                <input type="file" name="file" accept=".csv,.txt"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-emerald-600 file:text-white hover:file:bg-emerald-700" required>
            </div>
            <button type="submit" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-6 py-2 rounded-lg text-sm transition">{{ __('messages.Import Grades') }}</button>
        </form>
    </div>

    <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
        @if($terms->count() > 0)
            <div class="flex gap-2 mb-6">
                @foreach($terms as $term)
                    <button type="button" onclick="showTerm('{{ $term->id }}')"
                        class="term-tab px-4 py-2 rounded-lg text-sm transition {{ $loop->first ? 'bg-blue-600/20 text-blue-400 font-medium' : 'bg-[var(--bg-hover)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}"
                        data-term="{{ $term->id }}">
                        {{ $term->name }}
                    </button>
                @endforeach
            </div>

            @foreach($terms as $term)
                @php
                    $termGrades = $grades->where('term_id', $term->id)->keyBy('student_id');
                @endphp
                <div class="term-panel {{ $loop->first ? '' : 'hidden' }}" data-term="{{ $term->id }}">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm border-b border-[var(--border-main)]">
                                <th class="text-start pb-3">#</th>
                                <th class="text-start pb-3">{{ __('messages.Student Number') }}</th>
                                <th class="text-start pb-3">{{ __('messages.Name') }}</th>
                                <th class="text-start pb-3">{{ __('messages.Section') }}</th>
                                <th class="text-start pb-3">{{ __('messages.Score') }}</th>
                                <th class="text-start pb-3">{{ __('messages.Status') }}</th>
                                <th class="text-start pb-3">{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                @php $grade = $termGrades->get($student->id); @endphp
                                <tr class="border-b border-[var(--border-main)]">
                                    <td class="py-3 text-[var(--text-secondary)] text-sm">{{ $index + 1 }}</td>
                                    <td class="py-3 text-[var(--text-primary)] text-sm">{{ $student->student_number }}</td>
                                    <td class="py-3 text-[var(--text-primary)] text-sm">{{ $student->name }}</td>
                                    <td class="py-3 text-[var(--text-secondary)] text-sm">{{ $student->section ? __('messages.Section') . ' ' . $student->section->name : __('messages.No section') }}</td>
                                    <td class="py-3 text-[var(--text-primary)] text-sm font-medium">{{ $grade ? $grade->score . ' / ' . $subject->max_score : '-' }}</td>
                                    <td class="py-3">
                                        @if($grade)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium
                                                {{ $grade->isPending() ? 'bg-amber-500/15 text-amber-400' : ($grade->isApproved() ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400') }}">
                                                {{ ucfirst($grade->status) }}
                                            </span>
                                        @else
                                            <span class="text-[var(--text-tertiary)] text-xs">{{ __('messages.Not entered') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @if($grade && $grade->isPending())
                                            <div class="flex gap-1">
                                                <form method="POST" action="{{ route('supervisor.grades.approve', $grade) }}">
                                                    @csrf @method('PATCH')
                                                    <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">{{ __('messages.Approve') }}</button>
                                                </form>
                                                <form method="POST" action="{{ route('supervisor.grades.reject', $grade) }}">
                                                    @csrf @method('PATCH')
                                                    <button class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition">{{ __('messages.Reject') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No terms created yet.') }}</p>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function showTerm(termId) {
        document.querySelectorAll('.term-tab').forEach(t => {
            t.classList.remove('bg-blue-600/20', 'text-blue-400', 'font-medium');
            t.classList.add('bg-[var(--bg-hover)]', 'text-[var(--text-secondary)]');
        });
        document.querySelectorAll('.term-panel').forEach(p => p.classList.add('hidden'));

        document.querySelector(`.term-tab[data-term="${termId}"]`).classList.remove('bg-[var(--bg-hover)]', 'text-[var(--text-secondary)]');
        document.querySelector(`.term-tab[data-term="${termId}"]`).classList.add('bg-blue-600/20', 'text-blue-400', 'font-medium');
        document.querySelector(`.term-panel[data-term="${termId}"]`).classList.remove('hidden');
    }
</script>
@endpush
