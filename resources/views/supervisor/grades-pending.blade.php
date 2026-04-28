@extends('layouts.supervisor')

@section('title', 'Pending Grades - ALWEFAQ')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white">Pending Grades</h2>
        <form method="GET" class="flex gap-2">
            <select name="level_id" class="bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-1.5 text-sm outline-none">
                <option value="">All Levels</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ request('level_id') == (string)$level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-white/5 text-white px-3 py-1.5 rounded-lg text-sm border border-blue-500/20 hover:bg-white/10 transition">Filter</button>
        </form>
    </div>

    @if($grades->count() > 0)
        <form method="POST" action="{{ route('supervisor.grades.bulk-approve') }}">
            @csrf
            <div class="mb-4">
                <button type="submit" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-4 py-2 rounded-lg text-sm transition"
                    onclick="return confirm('Approve all selected grades?')">
                    Approve Selected
                </button>
            </div>

            <div class="bg-[#111827] rounded-xl border border-blue-500/10 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/5 text-gray-400 text-sm">
                            <th class="px-4 py-3 text-left"><input type="checkbox" id="select-all"></th>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Level</th>
                            <th class="px-4 py-3 text-left">Subject</th>
                            <th class="px-4 py-3 text-left">Term</th>
                            <th class="px-4 py-3 text-center">Score</th>
                            <th class="px-4 py-3 text-center">Max</th>
                            <th class="px-4 py-3 text-left">Entered By</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            <tr class="border-t border-blue-500/10">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="grade_ids[]" value="{{ $grade->id }}" class="grade-checkbox">
                                </td>
                                <td class="px-4 py-3 text-white text-sm">{{ $grade->student->name }} <span class="text-gray-500">({{ $grade->student->student_number }})</span></td>
                                <td class="px-4 py-3 text-gray-400 text-sm">{{ $grade->student->level->name }}</td>
                                <td class="px-4 py-3 text-gray-400 text-sm">{{ $grade->subject->name }}</td>
                                <td class="px-4 py-3 text-gray-400 text-sm">{{ $grade->term->name }}</td>
                                <td class="px-4 py-3 text-white text-sm text-center font-medium">{{ $grade->score }}</td>
                                <td class="px-4 py-3 text-gray-500 text-sm text-center">{{ $grade->subject->max_score }}</td>
                                <td class="px-4 py-3 text-gray-400 text-sm">{{ $grade->enteredBy->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <form method="POST" action="{{ route('supervisor.grades.approve', $grade) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('supervisor.grades.reject', $grade) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                onclick="return confirm('Reject this grade?')">Reject</button>
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
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-8 text-center">
            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <p class="text-gray-500">No pending grades to review.</p>
        </div>
    @endif

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.grade-checkbox').forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
