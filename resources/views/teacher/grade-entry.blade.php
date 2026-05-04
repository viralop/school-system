@extends('layouts.teacher')

@section('title', 'Enter Grades - ALWEFAQ')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[var(--text-primary)]">{{ __('messages.Enter Grades') }}</h2>
        <p class="text-[var(--text-secondary)] mt-1">{{ $subject->localizedName }} - {{ $term->localizedName }} - {{ $subject->level->localizedName }}</p>
        <p class="text-amber-400 text-sm mt-1">{{ __('messages.Max score per student:') }} {{ $subject->max_score }}</p>
    </div>

    <form method="POST" action="{{ route('teacher.grades.store') }}">
        @csrf
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
        <input type="hidden" name="term_id" value="{{ $term->id }}">

        <div class="mobile-scroll-table bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] shadow-[var(--shadow-card)]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm">
                        <th class="text-start px-4 py-3">{{ __('messages.Student Number') }}</th>
                        <th class="text-start px-4 py-3">{{ __('messages.Name') }}</th>
                        <th class="text-start px-4 py-3">{{ __('messages.Section') }}</th>
                        <th class="text-center px-4 py-3">Score (max {{ $subject->max_score }})</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        @php
                            $existingGrade = $student->grades->first();
                        @endphp
                        <tr class="border-t border-[var(--border-main)]">
                            <td class="px-4 py-3 text-[var(--text-primary)] text-sm">{{ $student->student_number }}</td>
                            <td class="px-4 py-3 text-[var(--text-primary)] text-sm">{{ $student->name }}</td>
                            <td class="px-4 py-3 text-[var(--text-secondary)] text-sm">{{ $student->section ? 'Section ' . $student->section->name : __('messages.No section') }}</td>
                            <td class="px-4 py-3">
                                <input type="number" name="grades[{{ $index }}][score]"
                                    value="{{ $existingGrade ? $existingGrade->score : '' }}"
                                    min="0" max="{{ $subject->max_score }}" step="0.01"
                                    class="w-24 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-1.5 text-sm text-center outline-none focus:ring-2 focus:ring-blue-500/40"
                                    required>
                                <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                @if($existingGrade)
                                    <span class="text-xs ml-2 {{ $existingGrade->isPending() ? 'text-amber-400' : ($existingGrade->isApproved() ? 'text-emerald-400' : 'text-red-400') }}">
                                        ({{ ucfirst($existingGrade->status) }})
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold px-8 py-2.5 rounded-lg transition shadow-lg shadow-blue-600/20">
                {{ __('messages.Save & Submit for Approval') }}
            </button>
        </div>
    </form>
@endsection
