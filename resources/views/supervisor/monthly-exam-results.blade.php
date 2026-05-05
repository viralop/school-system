@extends('layouts.supervisor')

@section('title', $exam->localizedName . ' - ' . __('messages.Results') . ' - خولة بنت الأزور')

@section('content')
    <div class="mb-6">
        <a href="{{ route('supervisor.monthly-exams.index') }}" class="text-blue-400/60 hover:text-blue-300 text-sm flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            {{ __('messages.Monthly Exams') }}
        </a>
    </div>

    <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-[var(--text-primary)]">{{ $exam->localizedName }}</h2>
                <p class="text-[var(--text-secondary)] text-sm">
                    {{ $exam->level->localizedName }}
                    @if($exam->date) | {{ $exam->date->format('Y-m-d') }} @endif
                    | {{ __('messages.Full Mark') }}: {{ $full_mark }}
                </p>
            </div>
        </div>
    </div>

    @if(count($results) > 0)
        <div class="mobile-scroll-table bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)]">
            <table class="w-full">
                <thead>
                    <tr class="bg-[var(--table-header-bg)] text-[var(--text-secondary)] text-sm">
                        <th class="px-4 py-3 text-start">#</th>
                        <th class="px-4 py-3 text-start">{{ __('messages.Student Number') }}</th>
                        <th class="px-4 py-3 text-start">{{ __('messages.Name') }}</th>
                        @foreach($exam->subjects as $examSubject)
                            <th class="px-4 py-3 text-center">{{ $examSubject->subject->localizedName }}<br><span class="text-xs opacity-60">({{ $examSubject->max_degree }})</span></th>
                        @endforeach
                        <th class="px-4 py-3 text-center">{{ __('messages.Total Score') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('messages.Full Mark') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('messages.Percentage') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $row)
                        <tr class="border-t border-[var(--border-main)]">
                            <td class="px-4 py-3 text-[var(--text-secondary)] text-sm">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-[var(--text-primary)] text-sm">{{ $row['student']->student_number }}</td>
                            <td class="px-4 py-3 text-[var(--text-primary)] text-sm">{{ $row['student']->name }}</td>
                            @foreach($row['subjects'] as $subjectResult)
                                <td class="px-4 py-3 text-center text-sm {{ $subjectResult['score'] !== null ? 'text-[var(--text-primary)]' : 'text-[var(--text-tertiary)]' }}">
                                    {{ $subjectResult['score'] ?? '-' }}
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-[var(--text-primary)] text-sm text-center font-bold">{{ $row['total_score'] }}</td>
                            <td class="px-4 py-3 text-[var(--text-secondary)] text-sm text-center">{{ $row['full_mark'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm font-bold {{ $row['percentage'] >= 50 ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $row['percentage'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-8 text-center">
            <p class="text-[var(--text-secondary)]">{{ __('messages.No results available yet.') }}</p>
        </div>
    @endif
@endsection
