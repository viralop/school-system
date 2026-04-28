<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0c1222; }
        select option { background: #111827; color: #fff; }
    </style>
</head>
<body class="min-h-screen">
    @php
        $student = \App\Models\Student::with(['level', 'section'])->find(session('student_id'));
        $grades = \App\Models\Grade::where('student_id', $student->id)
            ->where('status', 'approved')
            ->with(['subject', 'term'])
            ->get()
            ->groupBy('term_id');
        $levelGrade = app(\App\Services\GradeCalculationService::class)
            ->calculateLevelGrade($student->id, $student->level_id);
    @endphp

    <nav class="bg-[#111827]/80 border-b border-blue-500/10 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    </div>
                    <h1 class="text-lg font-bold text-white">ALWEFAQ <span class="text-purple-400 text-xs font-normal">Student</span></h1>
                </div>
                <span class="text-blue-300/50 text-sm">{{ $student->name }} ({{ $student->student_number }})</span>
            </div>
            <form method="POST" action="{{ route('student.logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-white text-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
            <h2 class="text-lg font-semibold text-white mb-4">My Profile</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Student Number</p>
                    <p class="text-white">{{ $student->student_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Name</p>
                    <p class="text-white">{{ $student->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Level</p>
                    <p class="text-white">{{ $student->level->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Section</p>
                    <p class="text-white">{{ $student->section->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Parent Phone</p>
                    <p class="text-white">{{ $student->parent_phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    <span class="px-2 py-1 rounded text-xs font-medium bg-emerald-500/15 text-emerald-400">Active</span>
                </div>
            </div>
        </div>

        @if($levelGrade)
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
                <h2 class="text-lg font-semibold text-white mb-4">Overall Result - {{ $student->level->name }}</h2>
                <div class="flex items-center gap-8">
                    <div class="text-center">
                        <p class="text-4xl font-bold {{ $levelGrade['passed'] ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $levelGrade['level_percentage'] }}%
                        </p>
                        <p class="text-gray-500 text-sm mt-1">Level Grade</p>
                    </div>
                    <div>
                        <span class="px-3 py-1.5 rounded text-sm font-medium
                            {{ $levelGrade['passed'] ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400' }}">
                            {{ $levelGrade['passed'] ? 'PASS' : 'FAIL' }}
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if($grades->count() > 0)
            @foreach($grades as $termId => $termGrades)
                <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
                    <h3 class="text-white font-semibold mb-3">{{ $termGrades->first()->term->name }}</h3>
                    <table class="w-full">
                        <thead>
                            <tr class="text-gray-500 text-sm border-b border-blue-500/10">
                                <th class="text-left pb-2">Subject</th>
                                <th class="text-center pb-2">Score</th>
                                <th class="text-center pb-2">Max</th>
                                <th class="text-center pb-2">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($termGrades as $grade)
                                <tr class="border-b border-blue-500/5">
                                    <td class="py-2 text-white text-sm">{{ $grade->subject->name }}</td>
                                    <td class="py-2 text-white text-sm text-center">{{ $grade->score }}</td>
                                    <td class="py-2 text-gray-400 text-sm text-center">{{ $grade->subject->max_score }}</td>
                                    <td class="py-2 text-white text-sm text-center font-medium">
                                        {{ round(($grade->score / $grade->subject->max_score) * 100, 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 text-center">
                <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                <h2 class="text-lg font-semibold text-white mb-2">My Grades</h2>
                <p class="text-gray-500 text-sm">No grades published yet.</p>
            </div>
        @endif
    </div>
</body>
</html>
