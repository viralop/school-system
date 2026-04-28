@extends('visitor.layout')

@section('title', app()->getLocale() === 'ar' ? 'عن المدرسة - ALWEFAQ' : 'About - ALWEFAQ')
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
        <h1 class="text-4xl font-bold text-white mb-8">{{ $locale === 'ar' ? 'عن مدرستنا' : 'About Our School' }}</h1>

        <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 mb-8">
            <p class="text-gray-300 leading-relaxed text-lg">{{ $about }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($email)
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                    <h3 class="text-gray-400 text-sm mb-2">{{ $locale === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</h3>
                    <p class="text-white">{{ $email }}</p>
                </div>
            @endif
            @if($phone)
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                    <h3 class="text-gray-400 text-sm mb-2">{{ $locale === 'ar' ? 'الهاتف' : 'Phone' }}</h3>
                    <p class="text-white">{{ $phone }}</p>
                </div>
            @endif
            @if($address)
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                    <h3 class="text-gray-400 text-sm mb-2">{{ $locale === 'ar' ? 'العنوان' : 'Address' }}</h3>
                    <p class="text-white">{{ $address }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
