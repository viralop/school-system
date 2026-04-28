@extends('visitor.layout')

@section('title', app()->getLocale() === 'ar' ? 'الرئيسية - ALWEFAQ' : 'Home - ALWEFAQ')
@section('nav-home-active', 'text-white font-medium')

@section('content')
    @php
        $locale = app()->getLocale();
        $about = \App\Models\SiteSetting::get('about_' . $locale);
        $achievements = \App\Models\Achievement::latest()->take(4)->get();
        $schoolName = \App\Models\SiteSetting::get('school_name_' . $locale, 'ALWEFAQ');
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-white mb-4">
                @lang('messages.Welcome to') {{ $schoolName }}
            </h1>
            <p class="text-gray-400 text-xl">@lang('messages.School Management System')</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 text-center">
                <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-blue-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">{{ $locale === 'ar' ? '3 مستويات' : '3 Levels' }}</h3>
                <p class="text-gray-400 text-sm">{{ $locale === 'ar' ? 'نظام تعليمي من ثلاث مراحل مع تتبع الدرجات' : 'Three-level education system with grade tracking' }}</p>
            </div>
            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 text-center">
                <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-green-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">{{ $locale === 'ar' ? 'حساب الدرجات' : 'Grade Calculation' }}</h3>
                <p class="text-gray-400 text-sm">{{ $locale === 'ar' ? 'حساب تلقائي للنتائج ومعدلات الفصول' : 'Automatic term and level grade calculations' }}</p>
            </div>
            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 text-center">
                <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-yellow-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">{{ $locale === 'ar' ? 'آمن' : 'Secure' }}</h3>
                <p class="text-gray-400 text-sm">{{ $locale === 'ar' ? 'نظام آمن للطلاب والمعلمين والمشرفين' : 'Secure system for students, teachers, and supervisors' }}</p>
            </div>
        </div>

        @if($about)
            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 mb-16">
                <h2 class="text-2xl font-bold text-white mb-4">{{ $locale === 'ar' ? 'عن المدرسة' : 'About the School' }}</h2>
                <p class="text-gray-300 leading-relaxed">{{ $about }}</p>
                <a href="{{ route('about') }}" class="inline-block mt-4 text-blue-400 hover:text-blue-300 text-sm">{{ $locale === 'ar' ? 'اقرأ المزيد ←' : 'Read more →' }}</a>
            </div>
        @endif

        @if($achievements->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-white mb-6">{{ $locale === 'ar' ? 'أحدث الإنجازات' : 'Latest Achievements' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($achievements as $achievement)
                        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                            <div class="w-10 h-10 rounded-lg bg-yellow-600/20 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0 1 16.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.023 6.023 0 0 1-2.77.896m0 0a6.023 6.023 0 0 1-2.77-.896"/></svg>
                            </div>
                            <h3 class="text-white font-semibold">{{ $achievement->title($locale) }}</h3>
                            @if($achievement->description($locale))
                                <p class="text-gray-400 text-sm mt-2">{{ $achievement->description($locale) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('achievements') }}" class="inline-block mt-4 text-blue-400 hover:text-blue-300 text-sm">{{ $locale === 'ar' ? 'عرض الكل ←' : 'View all →' }}</a>
            </div>
        @endif
    </div>
@endsection
