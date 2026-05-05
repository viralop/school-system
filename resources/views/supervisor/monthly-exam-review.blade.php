@extends('layouts.supervisor')

@section('title', __('messages.Review Grades') . ' - ' . $monthlyExam->localizedName . ' - خولة بنت الأزور')

@section('content')
    <div class="mb-6">
        <a href="{{ route('supervisor.monthly-exams.index') }}" class="text-blue-400/60 hover:text-blue-300 text-sm flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            {{ __('messages.Monthly Exams') }}
        </a>
    </div>

    <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)]">{{ $monthlyExam->localizedName }} - {{ __('messages.Review Grades') }}</h2>
                <p class="text-[var(--text-secondary)] text-sm">
                    {{ $monthlyExam->level->localizedName }}
                    @if($monthlyExam->date) | {{ $monthlyExam->date->format('Y-m-d') }} @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $pendingCount = 0;
                    $approvedCount = 0;
                    foreach ($students as $student) {
                        foreach ($student->grades as $g) {
                            if ($g->isPending()) $pendingCount++;
                            if ($g->isApproved()) $approvedCount++;
                        }
                    }
                @endphp
                @if($pendingCount > 0)
                    <span class="px-2 py-1 rounded text-xs font-medium bg-amber-500/15 text-amber-400">{{ $pendingCount }} {{ __('messages.Pending') }}</span>
                @endif
                <span class="px-2 py-1 rounded text-xs font-medium bg-emerald-500/15 text-emerald-400">{{ $approvedCount }} {{ __('messages.Approved') }}</span>
            </div>
        </div>
    </div>

    @php
        $examSubjects = $monthlyExam->subjects->keyBy('subject_id');
    @endphp

    <form method="POST" action="{{ route('supervisor.monthly-exams.bulk-approve', $monthlyExam) }}" id="review-form">
        @csrf

        <div class="flex flex-wrap gap-2 mb-4">
            <button type="submit" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-4 py-2 rounded-lg text-sm transition"
                onclick="return confirm('{{ __('messages.Approve all selected grades?') }}')">
                {{ __('messages.Approve Selected') }}
            </button>
            <button type="button" onclick="rejectSelected()" class="bg-red-500/15 hover:bg-red-500/25 text-red-400 px-4 py-2 rounded-lg text-sm transition">
                {{ __('messages.Reject Selected') }}
            </button>
            <button type="button" onclick="toggleCheckboxes(true)" class="bg-[var(--bg-hover)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-3 py-2 rounded-lg text-sm transition">
                {{ __('messages.Select All') }}
            </button>
            <button type="button" onclick="toggleCheckboxes(false)" class="bg-[var(--bg-hover)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-3 py-2 rounded-lg text-sm transition">
                {{ __('messages.Deselect All') }}
            </button>
        </div>

        <div class="mobile-scroll-table bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm">
                        <th class="px-3 py-3 text-start w-10"><input type="checkbox" id="select-all" onchange="toggleCheckboxes(this.checked)"></th>
                        <th class="px-3 py-3 text-start">{{ __('messages.Student Number') }}</th>
                        <th class="px-3 py-3 text-start">{{ __('messages.Name') }}</th>
                        @foreach($examSubjects as $examSubject)
                            <th class="px-3 py-3 text-center">{{ $examSubject->subject->localizedName }}<br><span class="text-xs opacity-60">({{ $examSubject->max_degree }})</span></th>
                        @endforeach
                        <th class="px-3 py-3 text-center">{{ __('messages.Total Score') }}</th>
                        <th class="px-3 py-3 text-center">{{ __('messages.Status') }}</th>
                        <th class="px-3 py-3 text-center">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $studentGrades = $student->grades->keyBy('subject_id');
                            $hasPending = $studentGrades->contains(fn($g) => $g->isPending());
                            $totalScore = $studentGrades->sum('score');
                            $allApproved = $studentGrades->count() > 0 && $studentGrades->every(fn($g) => $g->isApproved());
                        @endphp
                        <tr class="border-t border-[var(--border-main)] {{ $hasPending ? 'bg-amber-500/5' : '' }}">
                            <td class="px-3 py-3">
                                @if($hasPending)
                                    <input type="checkbox" name="grade_ids[]" value="{{ $studentGrades->first(fn($g) => $g->isPending())?->id }}" class="grade-checkbox w-4 h-4 rounded">
                                @endif
                            </td>
                            <td class="px-3 py-3 text-[var(--text-primary)] text-sm">{{ $student->student_number }}</td>
                            <td class="px-3 py-3 text-[var(--text-primary)] text-sm">{{ $student->name }}</td>
                            @foreach($examSubjects as $examSubject)
                                @php
                                    $grade = $studentGrades->get($examSubject->subject_id);
                                @endphp
                                <td class="px-3 py-3 text-center text-sm">
                                    @if($grade)
                                        <span class="{{ $grade->isPending() ? 'text-amber-400' : ($grade->isApproved() ? 'text-emerald-400' : 'text-red-400') }}">
                                            {{ $grade->score }}
                                        </span>
                                    @else
                                        <span class="text-[var(--text-tertiary)]">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-3 py-3 text-[var(--text-primary)] text-sm text-center font-medium">{{ $studentGrades->count() > 0 ? $totalScore : '-' }}</td>
                            <td class="px-3 py-3 text-center">
                                @if($studentGrades->isEmpty())
                                    <span class="text-[var(--text-tertiary)] text-xs">{{ __('messages.Not entered') }}</span>
                                @elseif($hasPending)
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-amber-500/15 text-amber-400">{{ __('messages.Pending') }}</span>
                                @elseif($allApproved)
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/15 text-emerald-400">{{ __('messages.Approved') }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-red-500/15 text-red-400">{{ __('messages.Rejected') }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($hasPending)
                                    <div class="flex gap-1 justify-center">
                                        @foreach($studentGrades->filter(fn($g) => $g->isPending()) as $pendingGrade)
                                            <input type="hidden" name="all_pending[]" value="{{ $pendingGrade->id }}">
                                        @endforeach
                                        <button type="button" onclick="approveStudent({{ $student->id }})" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">{{ __('messages.Approve') }}</button>
                                        <button type="button" onclick="rejectStudent({{ $student->id }})" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition">{{ __('messages.Reject') }}</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <form method="POST" action="{{ route('supervisor.monthly-exams.bulk-reject', $monthlyExam) }}" id="reject-form" class="hidden">
        @csrf
        <input type="hidden" name="grade_ids" id="reject-grade-ids" value="">
    </form>
@endsection

@push('scripts')
<script>
    function toggleCheckboxes(checked) {
        document.querySelectorAll('.grade-checkbox').forEach(cb => cb.checked = checked);
        document.getElementById('select-all').checked = checked;
    }

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.grade-checkbox:checked')).map(cb => cb.value);
    }

    function rejectSelected() {
        const ids = getSelectedIds();
        if (ids.length === 0) {
            alert('No grades selected.');
            return;
        }
        if (!confirm('{{ __("messages.Reject selected grades?") }}')) return;
        document.getElementById('reject-grade-ids').value = JSON.stringify(ids);
        document.getElementById('reject-form').querySelector('input[name="grade_ids"]').value = ids.join(',');
        
        const form = document.getElementById('reject-form');
        const input = document.getElementById('reject-grade-ids');
        input.value = '';
        ids.forEach(id => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'grade_ids[]';
            hidden.value = id;
            form.appendChild(hidden);
        });
        form.submit();
    }

    function approveStudent(studentId) {
        const row = event.target.closest('tr');
        const checkboxes = row.querySelectorAll('.grade-checkbox');
        checkboxes.forEach(cb => cb.checked = true);
        document.getElementById('review-form').submit();
    }

    function rejectStudent(studentId) {
        if (!confirm('{{ __("messages.Reject this grade?") }}')) return;
        const row = event.target.closest('tr');
        const checkboxes = row.querySelectorAll('.grade-checkbox');
        
        const form = document.getElementById('reject-form');
        checkboxes.forEach(cb => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'grade_ids[]';
            hidden.value = cb.value;
            form.appendChild(hidden);
        });
        form.submit();
    }
</script>
@endpush
