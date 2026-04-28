@extends('layouts.supervisor')

@section('title', 'Results - ALWEFAQ')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white">Academic Results</h2>
        <form method="GET" class="flex gap-2">
            <select name="level_id" class="bg-[#111827] text-white border border-blue-500/20 rounded-lg px-3 py-1.5 text-sm outline-none" onchange="this.form.submit()">
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ $selectedLevel?->id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if($selectedLevel && count($results) > 0)
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5 text-gray-400 text-sm">
                        <th class="px-4 py-3 text-left">Student Number</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Section</th>
                        @if($selectedLevel->terms->count() > 0)
                            @foreach($selectedLevel->terms->sortBy('order') as $term)
                                <th class="px-4 py-3 text-center">{{ $term->name }}</th>
                            @endforeach
                        @endif
                        <th class="px-4 py-3 text-center">Level Grade</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $row)
                        <tr class="border-t border-blue-500/10">
                            <td class="px-4 py-3 text-white text-sm">{{ $row['student']->student_number }}</td>
                            <td class="px-4 py-3 text-white text-sm">{{ $row['student']->name }}</td>
                            <td class="px-4 py-3 text-gray-400 text-sm">{{ $row['student']->section ? 'Section ' . $row['student']->section->name : 'No section' }}</td>
                            @if($row['level_grade'])
                                @foreach($selectedLevel->terms->sortBy('order') as $term)
                                    @php
                                        $termData = $row['level_grade']['terms'][$term->id] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center text-sm {{ $termData ? 'text-white' : 'text-gray-600' }}">
                                        {{ $termData ? $termData['percentage'] . '%' : '-' }}
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center text-sm font-bold text-white">
                                    {{ $row['level_grade']['level_percentage'] }}%
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($row['level_grade']['passed'])
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/15 text-emerald-400">Pass</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-red-500/15 text-red-400">Fail</span>
                                    @endif
                                </td>
                            @else
                                @if($selectedLevel->terms->count() > 0)
                                    @foreach($selectedLevel->terms as $t)
                                        <td class="px-4 py-3 text-gray-600 text-sm text-center">-</td>
                                    @endforeach
                                @endif
                                <td class="px-4 py-3 text-gray-600 text-sm text-center">-</td>
                                <td class="px-4 py-3 text-gray-600 text-sm text-center">No grades</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-[#111827] rounded-xl border border-blue-500/10 p-8 text-center">
            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
            <p class="text-gray-500">No results available yet. Approve grades first.</p>
        </div>
    @endif
@endsection
