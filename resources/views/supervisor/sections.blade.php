@extends('layouts.supervisor')

@section('title', 'Manage Sections - ALWEFAQ')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($levels as $level)
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">{{ $level->name }}</h2>

                <form method="POST" action="{{ route('supervisor.sections.store') }}" class="mb-4 flex gap-2">
                    @csrf
                    <input type="hidden" name="level_id" value="{{ $level->id }}">
                    <input type="text" name="name" maxlength="10"
                        class="flex-1 bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40"
                        placeholder="Section name (e.g. A)" required>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">Add</button>
                </form>

                @if($level->sections->count() > 0)
                    <div class="space-y-2">
                        @foreach($level->sections as $section)
                            <div class="flex items-center justify-between bg-white/5 rounded-lg px-4 py-2 border border-blue-500/5">
                                <span class="text-white text-sm">Section {{ $section->name }}</span>
                                <span class="text-gray-500 text-xs">{{ $section->students()->count() }} students</span>
                                <form method="POST" action="{{ route('supervisor.sections.destroy', $section) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs ml-3 transition"
                                        onclick="return confirm('Delete this section?')">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No sections yet.</p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
