@extends('layouts.supervisor')

@section('title', 'Subjects & Grades - ALWEFAQ')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="lg:col-span-1 space-y-4 md:space-y-6">
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Subject') }}</h2>
                <form method="POST" action="{{ route('supervisor.subjects.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Level') }}</label>
                        <select name="level_id"
                            class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->localizedName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Subject Name') }}</label>
                        <input type="text" name="name" placeholder="e.g. Math"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Subject Name') }} ({{ __('messages.Arabic') }})</label>
                        <input type="text" name="name_ar" placeholder="مثال: الرياضيات"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Max Score') }}</label>
                        <input type="number" name="max_score" placeholder="100" step="0.01" min="1"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Teacher') }}</label>
                        <select name="teacher_id"
                            class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                            <option value="">{{ __('messages.No teacher') }}</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-6 py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">{{ __('messages.Add') }}</button>
                </form>
            </div>

            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Import Subjects from File') }}</h2>
                <p class="text-[var(--text-secondary)] text-xs mb-3">{{ __('messages.CSV format') }}: <code class="bg-[var(--bg-input)] px-1.5 py-0.5 rounded">name, max_score, teacher_email</code></p>
                <form method="POST" action="{{ route('supervisor.subjects.import') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Level') }}</label>
                        <select name="level_id"
                            class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->localizedName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.File (CSV)') }}</label>
                        <input type="file" name="file" accept=".csv,.txt"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-600 file:text-white hover:file:bg-blue-700" required>
                    </div>
                    <button type="submit" class="w-full bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 px-6 py-2 rounded-lg text-sm transition">{{ __('messages.Import') }}</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 overflow-hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                @foreach($levels as $level)
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-semibold text-[var(--text-primary)]">{{ $level->localizedName }}</h2>
                            <span class="text-[var(--text-secondary)] text-xs">{{ $level->subjects->count() }} {{ __('messages.subjects') }}</span>
                        </div>

                        @if($level->subjects->count() > 0)
                            <div class="space-y-2">
                                @foreach($level->subjects as $subject)
                                    @php
                                        $pendingCount = $subject->grades()->where('status', 'pending')->count();
                                    @endphp
                                    <div class="bg-[var(--bg-card)] rounded-lg border border-[var(--border-main)] hover:border-blue-500/30 transition group relative">
                                        <a href="{{ route('supervisor.subjects.grades', $subject) }}" class="block px-4 py-3">
                                            @if($pendingCount > 0)
                                                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full" title="{{ $pendingCount }} pending grade(s)"></span>
                                            @endif
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-[var(--text-primary)] text-sm font-medium group-hover:text-blue-400 transition">{{ $subject->localizedName }}</p>
                                                    <p class="text-[var(--text-secondary)] text-xs">{{ __('messages.Max Score') }}: {{ $subject->max_score }} | {{ $subject->teacher ? $subject->teacher->name : __('messages.No teacher') }}</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    @if($pendingCount > 0)
                                                        <span class="text-red-400 text-xs font-medium">{{ $pendingCount }} {{ __('messages.pending') }}</span>
                                                    @endif
                                                    <svg class="w-4 h-4 text-[var(--text-secondary)] group-hover:text-blue-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="flex gap-2 px-4 pb-2">
                                            <button type="button" onclick="openEditSubjectModal({{ $subject->id }}, '{{ addslashes($subject->name) }}', {{ $subject->max_score }}, {{ $subject->teacher_id ?? 'null' }}, '{{ addslashes($subject->name_ar ?? '') }}')"
                                                class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 bg-blue-500/10 rounded transition">{{ __('messages.Edit') }}</button>
                                            <form method="POST" action="{{ route('supervisor.subjects.destroy', $subject) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                    onclick="return confirm('{{ __('messages.Confirm delete?') }}')">{{ __('messages.Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                                <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No subjects yet.') }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="edit-subject-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 w-full max-w-md mx-4">
            <h3 class="text-[var(--text-primary)] font-semibold mb-4">{{ __('messages.Edit Subject') }}</h3>
            <form method="POST" id="edit-subject-form">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Subject Name') }}</label>
                    <input type="text" name="name" id="edit-subject-name"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                </div>
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Subject Name') }} ({{ __('messages.Arabic') }})</label>
                    <input type="text" name="name_ar" id="edit-subject-name-ar"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                </div>
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Max Score') }}</label>
                    <input type="number" name="max_score" id="edit-subject-max" step="0.01" min="1"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                </div>
                <div class="mb-4">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Teacher') }}</label>
                    <select name="teacher_id" id="edit-subject-teacher"
                        class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">{{ __('messages.No teacher') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditSubjectModal()"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Cancel') }}</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditSubjectModal(id, name, maxScore, teacherId, nameAr) {
            document.getElementById('edit-subject-name').value = name;
            document.getElementById('edit-subject-name-ar').value = nameAr || '';
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
