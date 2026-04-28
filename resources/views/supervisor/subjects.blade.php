@extends('layouts.supervisor')

@section('title', 'Subjects & Grades - ALWEFAQ')

@section('content')
    <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Add Subject</h2>
        <form method="POST" action="{{ route('supervisor.subjects.store') }}" class="flex gap-3 items-end flex-wrap">
            @csrf
            <div class="flex-1 min-w-[150px]">
                <label class="block text-gray-400 text-sm mb-1">Level</label>
                <select name="level_id"
                    class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-gray-400 text-sm mb-1">Subject Name</label>
                <input type="text" name="name" placeholder="e.g. Math"
                    class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
            </div>
            <div class="w-28">
                <label class="block text-gray-400 text-sm mb-1">Max Score</label>
                <input type="number" name="max_score" placeholder="100" step="0.01" min="1"
                    class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-gray-400 text-sm mb-1">Teacher</label>
                <select name="teacher_id"
                    class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                    <option value="">No teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-6 py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">Add</button>
        </form>
    </div>

    <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Import Subjects from File</h2>
        <p class="text-gray-500 text-xs mb-3">CSV format: <code class="bg-white/5 px-1.5 py-0.5 rounded">name, max_score, teacher_email</code></p>
        <form method="POST" action="{{ route('supervisor.subjects.import') }}" enctype="multipart/form-data" class="flex gap-3 items-end flex-wrap">
            @csrf
            <div class="flex-1 min-w-[150px]">
                <label class="block text-gray-400 text-sm mb-1">Level</label>
                <select name="level_id"
                    class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-gray-400 text-sm mb-1">File (CSV, XLSX)</label>
                <input type="file" name="file" accept=".csv,.txt"
                    class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-1.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-600 file:text-white hover:file:bg-blue-700" required>
            </div>
            <button type="submit" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-6 py-2 rounded-lg text-sm transition">Import</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($levels as $level)
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-white">{{ $level->name }}</h2>
                    <span class="text-gray-500 text-xs">{{ $level->subjects->count() }} subjects</span>
                </div>

                @if($level->subjects->count() > 0)
                    <div class="space-y-2">
                        @foreach($level->subjects as $subject)
                            @php
                                $pendingCount = $subject->grades()->where('status', 'pending')->count();
                            @endphp
                            <div class="bg-[#111827] rounded-lg border border-blue-500/10 hover:border-blue-500/30 transition group relative">
                                <a href="{{ route('supervisor.subjects.grades', $subject) }}" class="block px-4 py-3">
                                    @if($pendingCount > 0)
                                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full" title="{{ $pendingCount }} pending grade(s)"></span>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-white text-sm font-medium group-hover:text-blue-400 transition">{{ $subject->name }}</p>
                                            <p class="text-gray-500 text-xs">Max: {{ $subject->max_score }} | {{ $subject->teacher ? $subject->teacher->name : 'No teacher' }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($pendingCount > 0)
                                                <span class="text-red-400 text-xs font-medium">{{ $pendingCount }} pending</span>
                                            @endif
                                            <svg class="w-4 h-4 text-gray-500 group-hover:text-blue-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                                <div class="flex gap-2 px-4 pb-2">
                                    <button type="button" onclick="openEditSubjectModal({{ $subject->id }}, '{{ addslashes($subject->name) }}', {{ $subject->max_score }}, {{ $subject->teacher_id ?? 'null' }})"
                                        class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 bg-blue-500/10 rounded transition">Edit</button>
                                    <form method="POST" action="{{ route('supervisor.subjects.destroy', $subject) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                            onclick="return confirm('Delete this subject?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
                        <p class="text-gray-500 text-sm">No subjects yet.</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div id="edit-subject-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 w-full max-w-md">
            <h3 class="text-white font-semibold mb-4">Edit Subject</h3>
            <form method="POST" id="edit-subject-form">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="block text-gray-400 text-sm mb-1">Subject Name</label>
                    <input type="text" name="name" id="edit-subject-name"
                        class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-400 text-sm mb-1">Max Score</label>
                    <input type="number" name="max_score" id="edit-subject-max" step="0.01" min="1"
                        class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-1">Teacher</label>
                    <select name="teacher_id" id="edit-subject-teacher"
                        class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">No teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditSubjectModal()"
                        class="text-gray-400 hover:text-white px-4 py-2 text-sm rounded-lg transition">Cancel</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 text-sm rounded-lg transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditSubjectModal(id, name, maxScore, teacherId) {
            document.getElementById('edit-subject-name').value = name;
            document.getElementById('edit-subject-max').value = maxScore;
            document.getElementById('edit-subject-teacher').value = teacherId || '';
            document.getElementById('edit-subject-form').action = '{{ url("/supervisor/subjects") }}/' + id;
            document.getElementById('edit-subject-modal').classList.remove('hidden');
        }

        function closeEditSubjectModal() {
            document.getElementById('edit-subject-modal').classList.add('hidden');
        }
    </script>
@endsection
