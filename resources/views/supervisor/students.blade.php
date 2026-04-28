@extends('layouts.supervisor')

@section('title', 'Manage Students - ALWEFAQ')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Add Student</h2>
            <form method="POST" action="{{ route('supervisor.students.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name"
                        class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-1">Parent Phone</label>
                    <input type="text" name="parent_phone"
                        class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-1">Level</label>
                    <select name="level_id" id="add-level"
                        class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                        required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4" id="add-section-wrapper">
                    <label class="block text-gray-400 text-sm font-medium mb-1">Section <span id="add-section-optional" class="text-gray-600 text-xs">(optional - no sections for this level)</span></label>
                    <select name="section_id" id="add-section"
                        class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">Select level first</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">
                    Add Student
                </button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-white mb-3">Students</h2>
                    <form method="GET" action="{{ route('supervisor.students.index') }}" id="filter-form" class="flex flex-wrap gap-2 items-center">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="bg-white/5 text-white border border-blue-500/20 rounded-lg pl-8 pr-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40 placeholder-gray-500"
                                placeholder="Name or number...">
                        </div>
                        <select name="level_id" id="filter-level"
                            class="bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                            onchange="document.getElementById('filter-form').submit()">
                            <option value="">All Levels</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ request('level_id') == (string) $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach>
                        </select>
                        @php $filterSections = request('level_id') ? \App\Models\Section::where('level_id', request('level_id'))->orderBy('name')->get() : collect(); @endphp
                        <select name="section_id" id="filter-section"
                            class="bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                            onchange="document.getElementById('filter-form').submit()">
                            <option value="">All Sections</option>
                            @foreach($filterSections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == (string) $section->id ? 'selected' : '' }}>Section {{ $section->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-1.5 rounded-lg text-sm transition shadow-md shadow-blue-600/15 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
                            Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('level_id') || request()->filled('section_id'))
                            <a href="{{ route('supervisor.students.index') }}" class="text-gray-400 hover:text-white text-xs px-2 py-1.5 transition">Clear</a>
                        @endif
                    </form>
                </div>

                @if($students->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-gray-500 text-sm border-b border-blue-500/10">
                                    <th class="text-left pb-3">Number</th>
                                    <th class="text-left pb-3">Name</th>
                                    <th class="text-left pb-3">Level</th>
                                    <th class="text-left pb-3">Section</th>
                                    <th class="text-left pb-3">Phone</th>
                                    <th class="text-left pb-3">Status</th>
                                    <th class="text-left pb-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr class="border-b border-blue-500/5">
                                        <td class="py-3 text-white text-sm">{{ $student->student_number }}</td>
                                        <td class="py-3 text-white text-sm">{{ $student->name }}</td>
                                        <td class="py-3 text-gray-400 text-sm">{{ $student->level->name }}</td>
                                        <td class="py-3 text-gray-400 text-sm">{{ $student->section ? 'Section ' . $student->section->name : 'No section' }}</td>
                                        <td class="py-3 text-gray-400 text-sm">{{ $student->parent_phone ?? '-' }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-0.5 rounded text-xs font-medium
                                                {{ $student->isFrozen() ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' }}">
                                                {{ $student->isFrozen() ? 'Frozen' : 'Active' }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex gap-1">
                                                <button type="button" onclick="openEditModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->parent_phone }}', {{ $student->level_id }}, {{ $student->section_id ?? 'null' }})"
                                                    class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 bg-blue-500/10 rounded transition">Edit</button>
                                                @if($student->isFrozen())
                                                    <form method="POST" action="{{ route('supervisor.students.unfreeze', $student) }}">
                                                        @csrf @method('PATCH')
                                                        <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">Unfreeze</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('supervisor.students.freeze', $student) }}">
                                                        @csrf @method('PATCH')
                                                        <button class="text-amber-400 hover:text-amber-300 text-xs px-2 py-1 bg-amber-500/10 rounded transition">Freeze</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('supervisor.students.destroy', $student) }}" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                        onclick="openDeleteModal(this)">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $students->withQueryString()->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No students found.</p>
                @endif
            </div>
        </div>
    </div>

    <div id="edit-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 w-full max-w-md">
            <h3 class="text-white font-semibold mb-4">Edit Student</h3>
            <form method="POST" id="edit-form">
                @csrf @method('PUT')
                <input type="hidden" name="edit_id" id="edit-id">
                <div class="mb-3">
                    <label class="block text-gray-400 text-sm mb-1">Name</label>
                    <input type="text" name="name" id="edit-name"
                        class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-400 text-sm mb-1">Parent Phone</label>
                    <input type="text" name="parent_phone" id="edit-phone"
                        class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-400 text-sm mb-1">Level</label>
                    <select name="level_id" id="edit-level"
                        class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none" required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-1">Section</label>
                    <select name="section_id" id="edit-section"
                        class="w-full bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none" required>
                        <option value="">Select level first</option>
                    </select>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditModal()"
                        class="text-gray-400 hover:text-white px-4 py-2 text-sm rounded-lg transition">Cancel</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 text-sm rounded-lg transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 w-full max-w-sm text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-500/15 flex items-center justify-center">
                <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">Delete Student?</h3>
            <p class="text-gray-400 text-sm mb-6" id="delete-student-name">This action cannot be undone. All student data will be permanently removed.</p>
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-5 py-2 text-sm rounded-lg bg-white/5 text-gray-300 hover:bg-white/10 transition">Cancel</button>
                <button type="button" id="confirm-delete-btn"
                    class="px-5 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition">Delete</button>
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
                        sectionSelect.innerHTML = '<option value="">No sections for this level</option>';
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
