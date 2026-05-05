@extends('layouts.supervisor')

@section('title', __('messages.Monthly Exams') . ' - خولة بنت الأزور')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 md:mb-6">
        <h2 class="text-2xl font-bold text-[var(--text-primary)]">{{ __('messages.Monthly Exams') }}</h2>
        <form method="GET" class="flex gap-2">
            <select name="level_id" class="bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm outline-none" onchange="this.form.submit()">
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ $selectedLevel?->id == $level->id ? 'selected' : '' }}>{{ $level->localizedName }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="lg:col-span-1">
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Create Monthly Exam') }}</h3>
                <form method="POST" action="{{ route('supervisor.monthly-exams.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Exam Name') }} (EN)</label>
                        <input type="text" name="name_en" placeholder="e.g. October Exam"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Exam Name') }} (AR)</label>
                        <input type="text" name="name_ar" placeholder="مثال: امتحان شهر أكتوبر"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Level') }}</label>
                        <select name="level_id" id="exam-level"
                            class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $selectedLevel?->id == $level->id ? 'selected' : '' }}>{{ $level->localizedName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Date') }}</label>
                        <input type="date" name="date"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                    </div>

                    <div>
                        <label class="block text-[var(--text-secondary)] text-sm mb-2">{{ __('messages.Select Subjects') }}</label>
                        <div id="subject-list" class="space-y-2">
                            @if($selectedLevel)
                                @foreach($selectedLevel->subjects as $subject)
                                    <div class="flex items-center gap-2 bg-[var(--bg-hover)] rounded-lg px-3 py-2">
                                        <input type="checkbox" name="subjects[{{ $loop->index }}][subject_id]" value="{{ $subject->id }}" id="sub-{{ $subject->id }}" class="subject-check w-4 h-4 rounded" checked>
                                        <label for="sub-{{ $subject->id }}" class="text-[var(--text-primary)] text-sm flex-1">{{ $subject->localizedName }}</label>
                                        <input type="number" name="subjects[{{ $loop->index }}][max_degree]" value="{{ $subject->default_max_degree }}" step="0.01" min="1" max="9999"
                                            class="w-20 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-2 py-1 text-sm text-center outline-none focus:ring-2 focus:ring-blue-500/40" required>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <p class="text-[var(--text-secondary)] text-xs mt-2">{{ __('messages.Full Mark') }}: <span id="full-mark">0</span></p>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-6 py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">{{ __('messages.Create') }}</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            @if($exams->count() > 0)
                <div class="space-y-4">
                    @foreach($exams as $exam)
                        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-[var(--text-primary)]">{{ $exam->localizedName }}</h3>
                                    <p class="text-[var(--text-secondary)] text-sm">
                                        {{ $exam->level->localizedName }}
                                        @if($exam->date) | {{ $exam->date->format('Y-m-d') }} @endif
                                        | {{ __('messages.Full Mark') }}: {{ $exam->totalFullMark() }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $exam->isOpen() ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400' }}">
                                        {{ $exam->isOpen() ? __('messages.Active') : __('messages.Frozen') }}
                                    </span>
                                    @php
                                        $examPending = \App\Models\Grade::where('exam_type', 'monthly')->where('exam_id', $exam->id)->where('status', 'pending')->count();
                                    @endphp
                                    @if($examPending > 0)
                                        <a href="{{ route('supervisor.monthly-exams.review', $exam) }}" class="text-amber-400 hover:text-amber-300 text-xs px-2 py-1 bg-amber-500/10 rounded transition flex items-center gap-1">
                                            {{ __('messages.Review') }} <span class="bg-amber-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ $examPending }}</span>
                                        </a>
                                    @endif
                                    @if($exam->isOpen())
                                        <form method="POST" action="{{ route('supervisor.monthly-exams.close', $exam) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-amber-400 hover:text-amber-300 text-xs px-2 py-1 bg-amber-500/10 rounded transition">{{ __('messages.Close') }}</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('supervisor.monthly-exams.open', $exam) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">{{ __('messages.Reopen') }}</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('supervisor.monthly-exams.results', $exam) }}" class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 bg-blue-500/10 rounded transition">{{ __('messages.Results') }}</a>
                                    <button type="button" onclick='openEditExamModal({{ $exam->id }}, {{ json_encode($exam->name_en) }}, {{ json_encode($exam->name_ar ?? '') }}, {{ json_encode($exam->date ? $exam->date->format('Y-m-d') : '') }}, {{ json_encode($exam->subjects->mapWithKeys(fn($s) => [$s->subject_id => ['subject' => $s->subject->localizedName, 'max_degree' => (float) $s->max_degree]])) }})'
                                        class="text-purple-400 hover:text-purple-300 text-xs px-2 py-1 bg-purple-500/10 rounded transition">{{ __('messages.Edit') }}</button>
                                    <form method="POST" action="{{ route('supervisor.monthly-exams.destroy', $exam) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                            onclick="return confirm('{{ __('messages.Confirm delete?') }}')">{{ __('messages.Delete') }}</button>
                                    </form>
                                </div>
                            </div>
                            <div class="mobile-scroll-table">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm">
                                            <th class="px-3 py-2 text-start">{{ __('messages.Subject') }}</th>
                                            <th class="px-3 py-2 text-center">{{ __('messages.Max Degree') }}</th>
                                            <th class="px-3 py-2 text-center">{{ __('messages.Grades Entered') }}</th>
                                            <th class="px-3 py-2 text-center">{{ __('messages.Pending') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($exam->subjects as $examSubject)
                                            <tr class="border-t border-[var(--border-main)]">
                                                <td class="px-3 py-2 text-[var(--text-primary)] text-sm">{{ $examSubject->subject->localizedName }}</td>
                                                <td class="px-3 py-2 text-[var(--text-secondary)] text-sm text-center">{{ $examSubject->max_degree }}</td>
                                                @php
                                                    $entered = \App\Models\Grade::where('exam_type', 'monthly')->where('exam_id', $exam->id)->where('subject_id', $examSubject->subject_id)->where('status', 'approved')->count();
                                                    $pending = \App\Models\Grade::where('exam_type', 'monthly')->where('exam_id', $exam->id)->where('subject_id', $examSubject->subject_id)->where('status', 'pending')->count();
                                                @endphp
                                                <td class="px-3 py-2 text-emerald-400 text-sm text-center">{{ $entered }}</td>
                                                <td class="px-3 py-2 text-amber-400 text-sm text-center">{{ $pending }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-8 text-center">
                    <svg class="w-12 h-12 text-[var(--text-tertiary)] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                    <p class="text-[var(--text-secondary)]">{{ __('messages.No monthly exams yet.') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div id="edit-exam-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto py-8">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 w-full max-w-lg mx-4">
            <h3 class="text-[var(--text-primary)] font-semibold mb-4">{{ __('messages.Edit Monthly Exam') }}</h3>
            <form method="POST" id="edit-exam-form" class="space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Exam Name') }} (EN)</label>
                    <input type="text" name="name_en" id="edit-name-en"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                </div>
                <div>
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Exam Name') }} (AR)</label>
                    <input type="text" name="name_ar" id="edit-name-ar"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                </div>
                <div>
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Date') }}</label>
                    <input type="date" name="date" id="edit-date"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                </div>
                <div>
                    <label class="block text-[var(--text-secondary)] text-sm mb-2">{{ __('messages.Select Subjects') }}</label>
                    <div id="edit-subject-list" class="space-y-2 max-h-64 overflow-y-auto"></div>
                    <p class="text-[var(--text-secondary)] text-xs mt-2">{{ __('messages.Full Mark') }}: <span id="edit-full-mark">0</span></p>
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeEditExamModal()"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Cancel') }}</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFullMark() {
            let total = 0;
            document.querySelectorAll('.subject-check').forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('.flex');
                    const input = row.querySelector('input[type="number"]');
                    total += parseFloat(input.value) || 0;
                }
            });
            document.getElementById('full-mark').textContent = total;
        }

        document.querySelectorAll('.subject-check, #subject-list input[type="number"]').forEach(el => {
            el.addEventListener('change', updateFullMark);
            el.addEventListener('input', updateFullMark);
        });
        updateFullMark();

        document.getElementById('exam-level').addEventListener('change', function() {
            window.location.href = '{{ route('supervisor.monthly-exams.index') }}?level_id=' + this.value;
        });

        function updateEditFullMark() {
            let total = 0;
            document.querySelectorAll('.edit-subject-check').forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('.flex');
                    const input = row.querySelector('input[type="number"]');
                    total += parseFloat(input.value) || 0;
                }
            });
            document.getElementById('edit-full-mark').textContent = total;
        }

        let allSubjects = @if($selectedLevel) {{ $selectedLevel->subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->localizedName, 'default_max' => (float) $s->default_max_degree])->toJson() }} @else [] @endif;

        function openEditExamModal(examId, nameEn, nameAr, date, currentSubjects) {
            document.getElementById('edit-name-en').value = nameEn;
            document.getElementById('edit-name-ar').value = nameAr;
            document.getElementById('edit-date').value = date;
            document.getElementById('edit-exam-form').action = '{{ url("/supervisor/monthly-exams") }}/' + examId;

            const list = document.getElementById('edit-subject-list');
            list.innerHTML = '';

            let idx = 0;
            allSubjects.forEach(function(sub) {
                const current = currentSubjects[sub.id];
                const checked = current !== undefined;
                const maxDeg = checked ? current.max_degree : sub.default_max;

                const div = document.createElement('div');
                div.className = 'flex items-center gap-2 bg-[var(--bg-hover)] rounded-lg px-3 py-2';

                const cb = document.createElement('input');
                cb.type = 'checkbox';
                cb.name = 'subjects[' + idx + '][subject_id]';
                cb.value = sub.id;
                cb.id = 'edit-sub-' + sub.id;
                cb.className = 'edit-subject-check w-4 h-4 rounded';
                if (checked) cb.checked = true;

                const label = document.createElement('label');
                label.htmlFor = 'edit-sub-' + sub.id;
                label.className = 'text-[var(--text-primary)] text-sm flex-1';
                label.textContent = sub.name;

                const input = document.createElement('input');
                input.type = 'number';
                input.name = 'subjects[' + idx + '][max_degree]';
                input.value = maxDeg;
                input.step = '0.01';
                input.min = '1';
                input.max = '9999';
                input.className = 'w-20 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-2 py-1 text-sm text-center outline-none focus:ring-2 focus:ring-blue-500/40';
                if (checked) input.required = true;

                cb.addEventListener('change', function() {
                    input.required = this.checked;
                    if (this.checked) {
                        input.value = sub.default_max;
                    }
                    updateEditFullMark();
                });

                input.addEventListener('input', updateEditFullMark);

                div.appendChild(cb);
                div.appendChild(label);
                div.appendChild(input);
                list.appendChild(div);
                idx++;
            });

            updateEditFullMark();
            document.getElementById('edit-exam-modal').classList.remove('hidden');
        }

        function closeEditExamModal() {
            document.getElementById('edit-exam-modal').classList.add('hidden');
        }
    </script>
@endsection
