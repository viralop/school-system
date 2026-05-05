@extends('layouts.supervisor')

@section('title', 'Site Content - خولة بنت الأزور')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6 mb-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.School Information') }}</h2>
                <form method="POST" action="{{ route('supervisor.content.settings') }}">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4">
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.School Name (English)') }}</label>
                            <input type="text" name="school_name_en" value="{{ $settings['school_name_en'] }}"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.School Name (Arabic)') }}</label>
                            <input type="text" name="school_name_ar" value="{{ $settings['school_name_ar'] }}" dir="rtl"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4">
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.About (English)') }}</label>
                            <textarea name="about_en" rows="4"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">{{ $settings['about_en'] }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.About (Arabic)') }}</label>
                            <textarea name="about_ar" rows="4" dir="rtl"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">{{ $settings['about_ar'] }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4">
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Contact Email') }}</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Contact Phone') }}</label>
                            <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mb-4">
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Address (English)') }}</label>
                            <input type="text" name="address_en" value="{{ $settings['address_en'] }}"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-[var(--text-secondary)] text-sm mb-1">{{ __('messages.Address (Arabic)') }}</label>
                            <input type="text" name="address_ar" value="{{ $settings['address_ar'] }}" dir="rtl"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-6 py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">{{ __('messages.Save Settings') }}</button>
                </form>
            </div>
        </div>

        <div>
            <div class="bg-[var(--bg-card)] rounded-xl border border-[var(--border-main)] p-6">
                <h2 class="text-lg font-semibold text-[var(--text-primary)] mb-4">{{ __('messages.Add Achievement') }}</h2>
                <form method="POST" action="{{ route('supervisor.content.achievements.store') }}" class="mb-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-[var(--text-secondary)] text-xs mb-1">{{ __('messages.Title (English)') }}</label>
                            <input type="text" name="title_en" required
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-[var(--text-secondary)] text-xs mb-1">{{ __('messages.Title (Arabic)') }}</label>
                            <input type="text" name="title_ar" dir="rtl" required
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-[var(--text-secondary)] text-xs mb-1">{{ __('messages.Description (English)') }}</label>
                            <textarea name="description_en" rows="2"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[var(--text-secondary)] text-xs mb-1">{{ __('messages.Description (Arabic)') }}</label>
                            <textarea name="description_ar" rows="2" dir="rtl"
                                class="w-full bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm outline-none"></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-[var(--text-secondary)] text-xs mb-1">{{ __('messages.Icon (emoji)') }}</label>
                        <input type="text" name="icon" placeholder="e.g. &#x1f3c6;" maxlength="50"
                            class="w-20 bg-[var(--bg-input)] text-[var(--text-primary)] border border-[var(--border-input)] rounded-lg px-3 py-2 text-sm text-center outline-none">
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg text-sm transition shadow-lg shadow-blue-600/20">{{ __('messages.Add Achievement') }}</button>
                </form>

                @if($achievements->count() > 0)
                    <h3 class="text-[var(--text-secondary)] text-sm mb-3">{{ __('messages.Existing Achievements') }} ({{ $achievements->count() }})</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($achievements as $a)
                            <div class="bg-[var(--bg-hover)] rounded-lg px-4 py-3 flex items-center justify-between border border-[var(--border-main)]">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">{{ $a->icon ?? '&#x1f3c6;' }}</span>
                                    <div>
                                        <p class="text-[var(--text-primary)] text-sm">{{ $a->title_en }}</p>
                                        <p class="text-[var(--text-secondary)] text-xs">{{ $a->title_ar }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('supervisor.content.achievements.destroy', $a) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs transition"
                                        onclick="return confirm('{{ __('messages.Confirm delete?') }}')">{{ __('messages.Delete') }}</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[var(--text-secondary)] text-sm">{{ __('messages.No achievements yet.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
