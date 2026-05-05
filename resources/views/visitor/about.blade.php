@extends('visitor.layout')

@section('title', app()->getLocale() === 'ar' ? 'عن المدرسة - خولة بنت الأزور' : 'About - خولة بنت الأزور')
@section('nav-about-active', 'text-white font-medium')

@section('content')
    @php
        $locale = app()->getLocale();
        $about = \App\Models\SiteSetting::get('about_' . $locale, $locale === 'ar' ? 'لا تتوفر معلومات بعد.' : 'No information available yet.');
        $phone = \App\Models\SiteSetting::get('contact_phone');
        $email = \App\Models\SiteSetting::get('contact_email');
        $address = \App\Models\SiteSetting::get('address_' . $locale);
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-2xl md:text-4xl font-bold text-white mb-4 md:mb-8">@lang('messages.About Our School')</h1>

        <div class="bg-white/10 backdrop-blur-md p-6 md:p-8 rounded-xl border border-white/20 mb-8">
            <p class="text-green-300/80 leading-relaxed text-base md:text-lg">{{ $about }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($email)
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <h3 class="text-green-300/60 text-sm mb-2">@lang('messages.Contact Email')</h3>
                    <p class="text-white">{{ $email }}</p>
                </div>
            @endif
            @if($phone)
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <h3 class="text-green-300/60 text-sm mb-2">@lang('messages.Contact Phone')</h3>
                    <p class="text-white">{{ $phone }}</p>
                </div>
            @endif
            @if($address)
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <h3 class="text-green-300/60 text-sm mb-2">@lang('messages.Address (English)')</h3>
                    <p class="text-white">{{ $address }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
