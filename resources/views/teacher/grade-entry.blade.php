<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Grades - ALWEFAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0c1222; }
        select option { background: #111827; color: #fff; }
    </style>
</head>
<body class="min-h-screen">
    <nav class="bg-[#111827]/80 border-b border-blue-500/10 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    </div>
                    <h1 class="text-lg font-bold text-white">ALWEFAQ <span class="text-emerald-400 text-xs font-normal">Teacher</span></h1>
                </div>
                <a href="{{ route('teacher.grades') }}" class="flex items-center gap-1.5 text-gray-400 hover:text-white text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to Grades
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-white text-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white">Enter Grades</h2>
            <p class="text-gray-400 mt-1">{{ $subject->name }} - {{ $term->name }} - {{ $subject->level->name }}</p>
            <p class="text-amber-400 text-sm mt-1">Max score per student: {{ $subject->max_score }}</p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.grades.store') }}">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
            <input type="hidden" name="term_id" value="{{ $term->id }}">

            <div class="bg-[#111827] rounded-xl border border-blue-500/10 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/5 text-gray-400 text-sm">
                            <th class="text-left px-4 py-3">Student Number</th>
                            <th class="text-left px-4 py-3">Name</th>
                            <th class="text-left px-4 py-3">Section</th>
                            <th class="text-center px-4 py-3">Score (max {{ $subject->max_score }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            @php
                                $existingGrade = $student->grades->first();
                            @endphp
                            <tr class="border-t border-blue-500/10">
                                <td class="px-4 py-3 text-white text-sm">{{ $student->student_number }}</td>
                                <td class="px-4 py-3 text-white text-sm">{{ $student->name }}</td>
                                <td class="px-4 py-3 text-gray-400 text-sm">{{ $student->section ? 'Section ' . $student->section->name : 'No section' }}</td>
                                <td class="px-4 py-3">
                                    <input type="number" name="grades[{{ $index }}][score]"
                                        value="{{ $existingGrade ? $existingGrade->score : '' }}"
                                        min="0" max="{{ $subject->max_score }}" step="0.01"
                                        class="w-24 bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-1.5 text-sm text-center outline-none focus:ring-2 focus:ring-blue-500/40"
                                        required>
                                    <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                    @if($existingGrade)
                                        <span class="text-xs ml-2 {{ $existingGrade->isPending() ? 'text-amber-400' : ($existingGrade->isApproved() ? 'text-emerald-400' : 'text-red-400') }}">
                                            ({{ ucfirst($existingGrade->status) }})
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold px-8 py-2.5 rounded-lg transition shadow-lg shadow-blue-600/20">
                    Save & Submit for Approval
                </button>
            </div>
        </form>
    </div>
</body>
</html>
