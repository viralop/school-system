@extends('layouts.supervisor')

@section('title', 'Manage Teachers - ALWEFAQ')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6 mb-6">
                <h2 class="text-lg font-semibold text-white mb-4">Add Teacher Emails</h2>
                <form method="POST" action="{{ route('supervisor.teachers.invite') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-medium mb-2">Email Addresses (one per line)</label>
                        <textarea name="emails" rows="4"
                            class="w-full bg-white/5 text-white border border-blue-500/20 rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/40"
                            placeholder="teacher1@gmail.com&#10;teacher2@gmail.com"
                            required></textarea>
                        @error('emails')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold px-6 py-2 rounded-lg transition shadow-lg shadow-blue-600/20">
                        Add Emails
                    </button>
                </form>
            </div>

            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Pre-Approved Emails</h2>
                @if($invites->count() > 0)
                    <div class="space-y-2">
                        @foreach($invites as $invite)
                            <div class="flex items-center justify-between bg-white/5 rounded-lg px-4 py-3 border border-blue-500/5">
                                <div>
                                    <p class="text-white text-sm">{{ $invite->email }}</p>
                                    <p class="text-xs {{ $invite->status === 'pending' ? 'text-amber-400' : 'text-emerald-400' }}">
                                        {{ ucfirst($invite->status) }}
                                    </p>
                                </div>
                                @if($invite->status === 'pending')
                                    <form method="POST" action="{{ route('supervisor.teachers.remove-invite', $invite) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition"
                                            onclick="return confirm('Remove this email?')">Remove</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No pre-approved emails yet.</p>
                @endif
            </div>
        </div>

        <div>
            <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Registered Teachers</h2>
                @if($teachers->count() > 0)
                    <div class="space-y-3">
                        @foreach($teachers as $teacher)
                            <div class="bg-white/5 rounded-lg px-4 py-4 border border-blue-500/5">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-white font-medium">{{ $teacher->name }}</p>
                                        <p class="text-gray-400 text-sm">{{ $teacher->email }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $teacher->isFrozen() ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' }}">
                                        {{ $teacher->isFrozen() ? 'Frozen' : 'Active' }}
                                    </span>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    @if($teacher->isFrozen())
                                        <form method="POST" action="{{ route('supervisor.teachers.unfreeze', $teacher) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 text-sm px-3 py-1.5 rounded-lg transition">
                                                Unfreeze
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('supervisor.teachers.freeze', $teacher) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-amber-500/15 hover:bg-amber-500/25 text-amber-400 text-sm px-3 py-1.5 rounded-lg transition"
                                                onclick="return confirm('Freeze this teacher?')">
                                                Freeze
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('supervisor.teachers.destroy', $teacher) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500/15 hover:bg-red-500/25 text-red-400 text-sm px-3 py-1.5 rounded-lg transition"
                                            onclick="return confirm('Delete this teacher permanently?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No registered teachers yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
