@extends('layouts.supervisor')

@section('title', __('messages.Manage Teachers') . ' - خولة بنت الأزور')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Teacher') }}</h2>
                <form method="POST" action="{{ route('supervisor.teachers.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Teacher Name') }}</label>
                        <input type="text" name="name"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="{{ __('messages.Teacher Name') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Teacher IDs (one per line)') }}</label>
                        <textarea name="teacher_ids" rows="4"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="TCH-001&#10;TCH-002"
                            required></textarea>
                        @error('teacher_ids')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold px-6 py-2 rounded-lg transition shadow-lg shadow-blue-600/20">
                        {{ __('messages.Add Teachers') }}
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Registered Teachers') }}</h2>
                @if($teachers->count() > 0)
                    <div class="space-y-3">
                        @foreach($teachers as $teacher)
                            <div class="bg-[var(--bg-hover)] rounded-lg px-4 py-4 border border-[var(--border-main)]">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-[var(--text-primary)] font-medium">{{ $teacher->name }}</p>
                                        <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.Teacher ID') }}: {{ $teacher->teacher_id }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $teacher->isFrozen() ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' }}">
                                        {{ $teacher->isFrozen() ? __('messages.Frozen') : __('messages.Active') }}
                                    </span>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button type="button" onclick="openEditModal({{ $teacher->id }}, '{{ $teacher->name }}', '{{ $teacher->teacher_id }}')"
                                        class="bg-blue-500/15 hover:bg-blue-500/25 text-blue-400 text-sm px-3 py-1.5 rounded-lg transition">
                                        {{ __('messages.Edit') }}
                                    </button>
                                    @if($teacher->isFrozen())
                                        <form method="POST" action="{{ route('supervisor.teachers.unfreeze', $teacher) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 text-sm px-3 py-1.5 rounded-lg transition">
                                                {{ __('messages.Unfreeze') }}
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('supervisor.teachers.freeze', $teacher) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-amber-500/15 hover:bg-amber-500/25 text-amber-400 text-sm px-3 py-1.5 rounded-lg transition"
                                                onclick="return confirm('{{ __('messages.Freeze this teacher?') }}')">
                                                {{ __('messages.Freeze') }}
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('supervisor.teachers.destroy', $teacher) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500/15 hover:bg-red-500/25 text-red-400 text-sm px-3 py-1.5 rounded-lg transition"
                                            onclick="return confirm('{{ __('messages.Delete this teacher permanently?') }}')">
                                            {{ __('messages.Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No registered teachers yet.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection

<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 w-full max-w-sm animate-scale-in">
        <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Edit Teacher') }}</h3>
        <form method="POST" action="" id="editForm">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Teacher Name') }}</label>
                <input type="text" name="name" id="editName"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40" required>
            </div>
            <div class="mb-6">
                <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Teacher ID') }}</label>
                <input type="text" name="teacher_id" id="editTeacherId"
                    class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40" required>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2 rounded-lg transition">
                    {{ __('messages.Save') }}
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="flex-1 bg-[var(--bg-hover)] hover:bg-[var(--border-hover)] text-[var(--text-primary)] font-semibold py-2 rounded-lg transition">
                    {{ __('messages.Cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, name, teacherId) {
        document.getElementById('editForm').action = '/supervisor/teachers/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editTeacherId').value = teacherId;
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
