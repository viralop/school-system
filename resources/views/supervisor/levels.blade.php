@extends('layouts.supervisor')

@section('title', 'Manage Levels - خولة بنت الأزور')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Level') }}</h2>
                <form method="POST" action="{{ route('supervisor.levels.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Level Name (English)') }}</label>
                        <input type="text" name="name_en"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="e.g. First Year" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Level Name (Arabic)') }}</label>
                        <input type="text" name="name_ar"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="مثال: السنة الأولى" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Order') }}</label>
                        <input type="number" name="order" min="1"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="1" required>
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold px-6 py-2 rounded-lg transition shadow-lg shadow-blue-600/20">
                        {{ __('messages.Create Level') }}
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Levels') }}</h2>
                @if($levels->count() > 0)
                    <div class="space-y-3">
                        @foreach($levels as $level)
                            <div class="bg-[var(--bg-hover)] rounded-lg px-4 py-4 border border-[var(--border-main)]">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-[var(--text-primary)] font-medium">{{ $level->localizedName }}</p>
                                        <p class="text-[var(--text-secondary)] text-xs">
                                            {{ $level->students_count }} {{ __('messages.students') }} |
                                            {{ $level->subjects_count }} {{ __('messages.subjects') }} |
                                            {{ $level->sections_count }} {{ __('messages.sections') }}
                                        </p>
                                    </div>
                                    <span class="text-[var(--text-tertiary)] text-xs">#{{ $level->order }}</span>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button type="button" onclick="openEditLevelModal({{ $level->id }}, '{{ addslashes($level->name) }}', '{{ addslashes($level->name_ar ?? '') }}', {{ $level->order }})"
                                        class="text-blue-400 hover:text-blue-300 text-sm px-3 py-1.5 rounded-lg bg-blue-500/10 transition">{{ __('messages.Edit') }}</button>
                                    <form method="POST" action="{{ route('supervisor.levels.destroy', $level) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500/15 hover:bg-red-500/25 text-red-400 text-sm px-3 py-1.5 rounded-lg transition"
                                            onclick="return confirm('{{ __('messages.Delete this level permanently?') }}')">{{ __('messages.Delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No levels yet.') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div id="edit-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 w-full max-w-md">
            <h3 class="text-[var(--text-primary)] font-semibold mb-4">{{ __('messages.Edit Level') }}</h3>
            <form method="POST" id="edit-form">
                @csrf @method('PUT')
                <input type="hidden" name="edit_id" id="edit-id">
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Level Name (English)') }}</label>
                    <input type="text" name="name_en" id="edit-name-en"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none" required>
                </div>
                <div class="mb-3">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Level Name (Arabic)') }}</label>
                    <input type="text" name="name_ar" id="edit-name-ar"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none" required>
                </div>
                <div class="mb-4">
                    <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Order') }}</label>
                    <input type="number" name="order" id="edit-order" min="1"
                        class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none" required>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditLevelModal()"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Cancel') }}</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 text-sm rounded-lg transition">{{ __('messages.Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditLevelModal(id, nameEn, nameAr, order) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name-en').value = nameEn;
            document.getElementById('edit-name-ar').value = nameAr;
            document.getElementById('edit-order').value = order;
            document.getElementById('edit-form').action = '{{ url("/supervisor/levels") }}/' + id;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditLevelModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }
    </script>
@endsection
