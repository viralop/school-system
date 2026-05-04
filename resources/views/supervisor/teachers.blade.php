@extends('layouts.supervisor')

@section('title', 'Manage Teachers - ALWEFAQ')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Teacher Emails') }}</h2>
                <form method="POST" action="{{ route('supervisor.teachers.invite') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Full Name') }}</label>
                        <input type="text" name="name"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="{{ __('messages.Full Name') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[var(--text-secondary)] text-sm font-medium mb-2">{{ __('messages.Email Addresses (one per line)') }}</label>
                        <textarea name="emails" rows="4"
                            class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="teacher1@gmail.com&#10;teacher2@gmail.com"
                            required></textarea>
                        @error('emails')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold px-6 py-2 rounded-lg transition shadow-lg shadow-blue-600/20">
                        {{ __('messages.Add Emails') }}
                    </button>
                </form>
            </div>

            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Pre-Approved Emails') }}</h2>
                @if($invites->count() > 0)
                    <div class="space-y-2">
                        @foreach($invites as $invite)
                            <div class="flex items-center justify-between bg-[var(--bg-hover)] rounded-lg px-4 py-3 border border-[var(--border-main)]">
                                <div>
                                    @if($invite->name)
                                        <p class="text-[var(--text-primary)] text-sm">{{ $invite->name }}</p>
                                    @endif
                                    <p class="{{ $invite->name ? 'text-[var(--text-secondary)] text-xs' : 'text-[var(--text-primary)] text-sm' }}">{{ $invite->email }}</p>
                                    <p class="text-xs {{ $invite->status === 'pending' ? 'text-amber-400' : 'text-emerald-400' }}">
                                        {{ ucfirst($invite->status) }}
                                    </p>
                                </div>
                                @if($invite->status === 'pending')
                                    <form method="POST" action="{{ route('supervisor.teachers.remove-invite', $invite) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition"
                                            onclick="return confirm('{{ __('messages.Remove this email?') }}')">{{ __('messages.Remove') }}</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No pre-approved emails yet.') }}</p>
                @endif
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
                                        <p class="text-[var(--text-secondary)] text-sm">{{ $teacher->email }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $teacher->isFrozen() ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' }}">
                                        {{ $teacher->isFrozen() ? __('messages.Frozen') : __('messages.Active') }}
                                    </span>
                                </div>
                                <div class="flex gap-2 mt-3">
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
                        @endforeach>
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No registered teachers yet.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
