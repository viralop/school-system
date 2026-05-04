@extends('visitor.layout')

@section('title', app()->getLocale() === 'ar' ? 'الإنجازات - ALWEFAQ' : 'Achievements - ALWEFAQ')
@section('nav-achievements-active', 'text-white font-medium')

@section('content')
    @php
        $locale = app()->getLocale();
        $achievements = \App\Models\Achievement::latest()->get();
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-2xl md:text-4xl font-bold text-white mb-4 md:mb-8">@lang('messages.Our Achievements')</h1>

        @if($achievements->count() > 0)
            <div class="space-y-4">
                @foreach($achievements as $achievement)
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20 flex items-start gap-3 sm:gap-4">
                        <div class="text-3xl flex-shrink-0">{{ $achievement->icon ?? '&#x1f3c6;' }}</div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">{{ $achievement->title($locale) }}</h3>
                            @if($achievement->description($locale))
                                <p class="text-green-300/80 mt-2 leading-relaxed">{{ $achievement->description($locale) }}</p>
                            @endif
                            <p class="text-white/40 text-xs mt-3">{{ $achievement->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl border border-white/20 text-center">
                <p class="text-green-300/60">@lang('messages.No achievements yet.')</p>
            </div>
        @endif
    </div>
@endsection
