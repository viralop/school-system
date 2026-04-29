@extends('layouts.supervisor')

@section('title', 'Manage Sections - ALWEFAQ')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($levels as $level)
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ $level->name }}</h2>

                <form method="POST" action="{{ route('supervisor.sections.store') }}" class="mb-4 flex gap-2">
                    @csrf
                    <input type="hidden" name="level_id" value="{{ $level->id }}">
                    <input type="text" name="name" maxlength="10"
                        class="flex-1 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                        placeholder="{{ __('messages.Section name (e.g. A)') }}" required>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">{{ __('messages.Add') }}</button>
                </form>

                @if($level->sections->count() > 0)
                    <div class="space-y-2">
                        @foreach($level->sections as $section)
                            <div class="flex items-center justify-between bg-[var(--bg-hover)] rounded-lg px-4 py-2 border border-[var(--border-main)]">
                                <span class="text-[var(--text-primary)] text-sm">{{ __('messages.Section') }} {{ $section->name }}</span>
                                <span class="text-[var(--text-secondary)] text-xs">{{ $section->students()->count() }} {{ __('messages.students') }}</span>
                                <form method="POST" action="{{ route('supervisor.sections.destroy', $section) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs ml-3 transition"
                                        onclick="return confirm('{{ __('messages.Confirm delete?') }}')">{{ __('messages.Delete') }}</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No sections yet.') }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
