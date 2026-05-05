@extends('layouts.supervisor')

@section('title', 'Manage Students - خولة بنت الأزور')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
            <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Student') }}</h2>
            <form method="POST" action="{{ route('supervisor.students.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-[var(--text-secondary)] text-sm font-medium mb-1">{{ __('messages.Name') }}</label>
                    <input type="text" name="name"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-[var(--text-secondary)] text-sm font-medium mb-1">{{ __('messages.Parent Phone') }}</label>
                    <input type="text" name="parent_phone"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                </div>

                <div class="mb-4">
                    <label class="block text-[var(--text-secondary)] text-sm font-medium mb-1">{{ __('messages.Level') }}</label>
                    <select name="level_id" id="add-level"
                        class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                        required>
                        <option value="">{{ __('messages.Select Level') }}</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->localizedName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4" id="add-section-wrapper">
                    <label class="block text-[var(--text-secondary)] text-sm font-medium mb-1">{{ __('messages.Section') }} <span id="add-section-optional" class="text-[var(--text-tertiary)] text-xs">({{ __('messages.optional - no sections for this level') }})</span></label>
                    <select name="section_id" id="add-section"
                        class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">{{ __('messages.Select level first') }}</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">
                    {{ __('messages.Add Student') }}
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 overflow-hidden">
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-3">{{ __('messages.Students') }}</h2>
                    <form method="GET" action="{{ route('supervisor.students.index') }}" id="filter-form" class="flex flex-wrap gap-2 items-center text-sm">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-[var(--text-secondary)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg pl-8 pr-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 placeholder-[var(--text-placeholder)]"
                                placeholder="{{ __('messages.Name or number...') }}">
                        </div>
                        <select name="level_id" id="filter-level"
                            class="bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                            onchange="document.getElementById('filter-form').submit()">
                            <option value="">{{ __('messages.All Levels') }}</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ request('level_id') == (string) $level->id ? 'selected' : '' }}>{{ $level->localizedName }}</option>
                            @endforeach
                        </select>
                        @php $filterSections = request('level_id') ? \App\Models\Section::where('level_id', request('level_id'))->orderBy('name')->get() : collect(); @endphp
                        <select name="section_id" id="filter-section"
                            class="bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                            onchange="document.getElementById('filter-form').submit()">
                            <option value="">{{ __('messages.All Sections') }}</option>
                            @foreach($filterSections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == (string) $section->id ? 'selected' : '' }}>{{ __('messages.Section') }} {{ $section->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-1.5 rounded-lg text-sm transition shadow-md shadow-blue-600/15 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
                            {{ __('messages.Filter') }}
                        </button>
                        @if(request()->filled('search') || request()->filled('level_id') || request()->filled('section_id'))
                            <a href="{{ route('supervisor.students.index') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs px-2 py-1.5 transition">{{ __('messages.Clear') }}</a>
                        @endif
                    </form>
                </div>

                @if($students->count() > 0)
                    <div class="mobile-scroll-table overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm border-b border-[var(--border-main)]">
                                    <th class="text-start pb-3">{{ __('messages.Number') }}</th>
                                    <th class="text-start pb-3">{{ __('messages.Name') }}</th>
                                    <th class="text-start pb-3">{{ __('messages.Level') }}</th>
                                    <th class="text-start pb-3">{{ __('messages.Section') }}</th>
                                    <th class="text-start pb-3">{{ __('messages.Phone') }}</th>
                                    <th class="text-start pb-3">{{ __('messages.Status') }}</th>
                                    <th class="text-start pb-3">{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr class="border-b border-[var(--border-main)]">
                                        <td class="py-3 text-[var(--text-primary)] text-sm">{{ $student->student_number }}</td>
                                        <td class="py-3 text-[var(--text-primary)] text-sm">{{ $student->name }}</td>
                                        <td class="py-3 text-[var(--text-secondary)] text-sm">{{ $student->level->localizedName }}</td>
                                        <td class="py-3 text-[var(--text-secondary)] text-sm">{{ $student->section ? __('messages.Section') . ' ' . $student->section->name : __('messages.No section') }}</td>
                                        <td class="py-3 text-[var(--text-secondary)] text-sm">{{ $student->parent_phone ?? '-' }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-0.5 rounded text-xs font-medium
                                                {{ $student->isFrozen() ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' }}">
                                                {{ $student->isFrozen() ? __('messages.Frozen') : __('messages.Active') }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex gap-1">
                                                <button type="button" onclick="openEditModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->parent_phone }}', {{ $student->level_id }}, {{ $student->section_id ?? 'null' }})"
                                                    class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 bg-blue-500/10 rounded transition">{{ __('messages.Edit') }}</button>
                                                @if($student->isFrozen())
                                                    <form method="POST" action="{{ route('supervisor.students.unfreeze', $student) }}">
                                                        @csrf @method('PATCH')
                                                        <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">{{ __('messages.Unfreeze') }}</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('supervisor.students.freeze', $student) }}">
                                                        @csrf @method('PATCH')
                                                        <button class="text-amber-400 hover:text-amber-300 text-xs px-2 py-1 bg-amber-500/10 rounded transition">{{ __('messages.Freeze') }}</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('supervisor.students.destroy', $student) }}" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                        onclick="openDeleteModal(this)">{{ __('messages.Delete') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        {{ $students->withQueryString()->links() }}
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No students found.') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div id="edit-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 w-full max-w-md">
            <h3 class="text-[var(--text-primary)] font-semibold mb-4">{{ __('messages.Edit Student') }}</h3>
            <form method="POST" id="edit-form">
                @csrf @method('PUT')
                <input type="hidden" name="edit_id" id="edit-id">
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Name') }}</label>
                    <input type="text" name="name" id="edit-name"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none" required>
                </div>
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Parent Phone') }}</label>
                    <input type="text" name="parent_phone" id="edit-phone"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                </div>
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Level') }}</label>
                    <select name="level_id" id="edit-level"
                        class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none" required>
                        <option value="">{{ __('messages.Select Level') }}</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->localizedName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Section') }}</label>
                    <select name="section_id" id="edit-section"
                        class="w-full bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none" required>
                        <option value="">{{ __('messages.Select level first') }}</option>
                    </select>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditModal()"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Cancel') }}</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 w-full max-w-sm text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-500/15 flex items-center justify-center">
                <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="text-[var(--text-primary)] text-lg font-semibold mb-2">{{ __('messages.Delete Student') }}?</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-6" id="delete-student-name">{{ __('messages.This action cannot be undone. All student data will be permanently removed.') }}</p>
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-5 py-2 text-sm rounded-lg bg-[var(--bg-input)] text-[var(--text-secondary)] hover:bg-[var(--bg-hover)] transition">{{ __('messages.Cancel') }}</button>
                <button type="button" id="confirm-delete-btn"
                    class="px-5 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition">{{ __('messages.Delete') }}</button>
            </div>
        </div>
    </div>

    <script>
        let pendingDeleteForm = null;

        function openDeleteModal(btn) {
            pendingDeleteForm = btn.closest('form');
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            pendingDeleteForm = null;
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (pendingDeleteForm) {
                pendingDeleteForm.submit();
            }
        });

        function openEditModal(id, name, phone, levelId, sectionId) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-level').value = levelId;
            loadEditSections(levelId, sectionId);
            document.getElementById('edit-form').action = '{{ url("/supervisor/students") }}/' + id;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function loadEditSections(levelId, selectedId) {
            const sel = document.getElementById('edit-section');
            sel.innerHTML = '<option value="">Loading...</option>';
            fetch(`/api/sections?level_id=${levelId}`)
                .then(r => r.json())
                .then(sections => {
                    sel.innerHTML = '<option value="">Select Section</option>';
                    sections.forEach(s => {
                        sel.innerHTML += `<option value="${s.id}" ${s.id == selectedId ? 'selected' : ''}>Section ${s.name}</option>`;
                    });
                });
        }

        document.getElementById('edit-level').addEventListener('change', function() {
            loadEditSections(this.value, null);
        });

        document.getElementById('add-level').addEventListener('change', function() {
            const levelId = this.value;
            const sectionSelect = document.getElementById('add-section');
            const optionalLabel = document.getElementById('add-section-optional');
            sectionSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/api/sections?level_id=${levelId}`)
                .then(r => r.json())
                .then(sections => {
                    if (sections.length === 0) {
                        sectionSelect.innerHTML = '<option value="">{{ __('messages.No sections for this level') }}</option>';
                        sectionSelect.removeAttribute('required');
                        optionalLabel.style.display = 'inline';
                    } else {
                        sectionSelect.innerHTML = '<option value="">Select Section</option>';
                        sections.forEach(s => {
                            sectionSelect.innerHTML += `<option value="${s.id}">Section ${s.name}</option>`;
                        });
                        sectionSelect.setAttribute('required', 'required');
                        optionalLabel.style.display = 'none';
                    }
                });
        });
    </script>
@endsection
