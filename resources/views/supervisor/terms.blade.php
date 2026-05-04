@extends('layouts.supervisor')

@section('title', 'Manage Terms - ALWEFAQ')

@section('content')
    <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
        <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Term (applies to all levels)') }}</h2>
        <form method="POST" action="{{ route('supervisor.terms.store') }}" class="space-y-2">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="name" placeholder="{{ __('messages.Term name (e.g. First Term)') }}"
                    class="flex-1 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40" required>
                <input type="text" name="name_ar" placeholder="مثال: الفصل الأول"
                    class="flex-1 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500/40">
            </div>
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">{{ __('messages.Add to All Levels') }}</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @foreach($levels as $level)
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-[var(--text-primary)]">{{ $level->localizedName }}</h2>
                    <span class="text-[var(--text-secondary)] text-xs">{{ $level->terms->count() }} {{ __('messages.terms') }}</span>
                </div>

                @if($level->terms->count() > 0)
                    <div class="space-y-2">
                        @foreach($level->terms->sortBy('order') as $term)
                            <div class="bg-[var(--bg-hover)] rounded-lg px-4 py-3 border border-[var(--border-main)]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-[var(--text-primary)] text-sm font-medium">{{ $term->localizedName }}</p>
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
                                                    onclick="return confirm('{{ __('messages.Close this term?') }}')">{{ __('messages.Close') }}</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('supervisor.terms.open', $term) }}">
                                                @csrf @method('PATCH')
                                                <button class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 bg-emerald-500/10 rounded transition">{{ __('messages.Reopen') }}</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('supervisor.terms.destroy', $term) }}">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-300 text-xs px-2 py-1 bg-red-500/10 rounded transition"
                                                onclick="return confirm('{{ __('messages.Delete this term?') }}')">{{ __('messages.Delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No terms yet.') }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
