@extends('layouts.supervisor')

@section('title', 'Manage Terms - ALWEFAQ')

@section('content')
    <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Add Term (applies to all levels)</h2>
        <form method="POST" action="{{ route('supervisor.terms.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Term name (e.g. First Term)"
                class="flex-1 bg-white/5 text-white border border-blue-500/20 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">Add to All Levels</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($levels as $level)
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white">{{ $level->name }}</h2>
                    <span class="text-gray-500 text-xs">{{ $level->terms->count() }} terms</span>
                </div>

                @if($level->terms->count() > 0)
                    <div class="space-y-2">
                        @foreach($level->terms->sortBy('order') as $term)
                            <div class="bg-white/5 rounded-lg px-4 py-3 border border-blue-500/5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ $term->name }}</p>
                                        <p class="text-xs mt-1
                                            {{ $term->isOpen() ? 'text-emerald-400' : ($term->isGraded() ? 'text-blue-400' : 'text-amber-400') }}">
                                            {{ ucfirst($term->status) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($term->isOpen())
                                            <form method="POST" action="{{ route('supervisor.terms.close', $term) }}">
                                                @csrf @method('PATCH')
                                                <button class="text-amber-400 hover:text-amber-300 text-xs px-2 py-1 bg-amber-500/10 rounded transition"
                                                    onclick="return confirm('Close this term?')">Close</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('supervisor.terms.open', $term) }}">
                                                @csrf @method('PATCH')
                                                <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">Reopen</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('supervisor.terms.destroy', $term) }}">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                onclick="return confirm('Delete this term?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No terms yet.</p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
