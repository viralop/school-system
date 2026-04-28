@extends('layouts.supervisor')

@section('title', 'Supervisor Dashboard - ALWEFAQ')

@section('content')
    @php
        $studentCount = \App\Models\Student::count();
        $teacherCount = \App\Models\User::where('role', 'teacher')->count();
        $subjectCount = \App\Models\Subject::count();
        $termCount = \App\Models\Term::distinct()->count('name');
        $pendingGrades = \App\Models\Grade::where('status', 'pending')->count();
    @endphp

    <h2 class="text-2xl font-bold text-white mb-6">Dashboard</h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        <div class="bg-[#111827] p-6 rounded-xl border border-blue-500/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                </div>
                <h3 class="text-gray-400 text-sm">Students</h3>
            </div>
            <p class="text-3xl font-bold text-white">{{ $studentCount }}</p>
        </div>
        <div class="bg-[#111827] p-6 rounded-xl border border-blue-500/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                </div>
                <h3 class="text-gray-400 text-sm">Teachers</h3>
            </div>
            <p class="text-3xl font-bold text-white">{{ $teacherCount }}</p>
        </div>
        <div class="bg-[#111827] p-6 rounded-xl border border-blue-500/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3 class="text-gray-400 text-sm">Subjects</h3>
            </div>
            <p class="text-3xl font-bold text-white">{{ $subjectCount }}</p>
        </div>
        <div class="bg-[#111827] p-6 rounded-xl border border-blue-500/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </div>
                <h3 class="text-gray-400 text-sm">Terms</h3>
            </div>
            <p class="text-3xl font-bold text-white">{{ $termCount }}</p>
        </div>
        <div class="bg-[#111827] p-6 rounded-xl border border-blue-500/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <h3 class="text-gray-400 text-sm">Pending</h3>
            </div>
            <p class="text-3xl font-bold text-red-400">{{ $pendingGrades }}</p>
        </div>
    </div>
@endsection
