@extends('layouts.supervisor')

@section('title', 'Pending Grades - خولة بنت الأزور')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 md:mb-6">
        <h2 class="text-2xl font-bold text-[var(--text-primary)]">{{ __('messages.Pending Grades') }}</h2>
        <form method="GET" class="flex gap-2">
            <select name="level_id" class="bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm outline-none">
                <option value="">{{ __('messages.All Levels') }}</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ request('level_id') == (string)$level->id ? 'selected' : '' }}>{{ $level->localizedName }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-[var(--bg-input)] text-[var(--text-primary)] px-3 py-1.5 rounded-lg text-sm border border-[var(--border-input)] hover:bg-[var(--bg-hover)] transition">{{ __('messages.Filter') }}</button>
        </form>
    </div>

    @if($grades->count() > 0)
        <form method="POST" action="{{ route('supervisor.grades.bulk-approve') }}">
            @csrf
            <div class="mb-4">
                <button type="submit" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-4 py-2 rounded-lg text-sm transition"
                    onclick="return confirm('{{ __('messages.Approve all selected grades?') }}')">
                    {{ __('messages.Approve Selected') }}
                </button>
            </div>

            <div class="mobile-scroll-table bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm">
                            <th class="px-4 py-3 text-start"><input type="checkbox" id="select-all"></th>
                            <th class="px-4 py-3 text-start">{{ __('messages.Student') }}</th>
                            <th class="px-4 py-3 text-start">{{ __('messages.Level') }}</th>
                            <th class="px-4 py-3 text-start">{{ __('messages.Subject') }}</th>
                            <th class="px-4 py-3 text-start">{{ __('messages.Term') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('messages.Score') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('messages.Max') }}</th>
                            <th class="px-4 py-3 text-start">{{ __('messages.Entered By') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            <tr class="border-t border-[var(--border-main)]">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="grade_ids[]" value="{{ $grade->id }}" class="grade-checkbox">
                                </td>
                                <td class="px-4 py-3 text-[var(--text-primary)] text-sm">{{ $grade->student->name }} <span class="text-[var(--text-secondary)]">({{ $grade->student->student_number }})</span></td>
                                <td class="px-4 py-3 text-[var(--text-secondary)] text-sm">{{ $grade->student->level->localizedName }}</td>
                                <td class="px-4 py-3 text-[var(--text-secondary)] text-sm">{{ $grade->subject->localizedName }}</td>
                                <td class="px-4 py-3 text-[var(--text-secondary)] text-sm">{{ $grade->term ? $grade->term->localizedName : '-' }}</td>
                                <td class="px-4 py-3 text-[var(--text-primary)] text-sm text-center font-medium">{{ $grade->score }}</td>
                                <td class="px-4 py-3 text-[var(--text-secondary)] text-sm text-center">{{ $grade->subject->default_max_degree }}</td>
                                <td class="px-4 py-3 text-[var(--text-secondary)] text-sm">{{ $grade->enteredBy->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <form method="POST" action="{{ route('supervisor.grades.approve', $grade) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">{{ __('messages.Approve') }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('supervisor.grades.reject', $grade) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                onclick="return confirm('{{ __('messages.Reject this grade?') }}')">{{ __('messages.Reject') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $grades->withQueryString()->links() }}</div>
        </form>
    @else
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-8 text-center">
            <svg class="w-12 h-12 text-[var(--text-tertiary)] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <p class="text-[var(--text-secondary)]">{{ __('messages.No pending grades to review.') }}</p>
        </div>
    @endif

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.grade-checkbox').forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
