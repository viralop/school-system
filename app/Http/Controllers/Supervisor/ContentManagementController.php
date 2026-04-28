<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    public function edit()
    {
        $settings = [
            'school_name_en' => SiteSetting::get('school_name_en', 'ALWEFAQ'),
            'school_name_ar' => SiteSetting::get('school_name_ar', 'ALWEFAQ'),
            'about_en' => SiteSetting::get('about_en'),
            'about_ar' => SiteSetting::get('about_ar'),
            'contact_email' => SiteSetting::get('contact_email'),
            'contact_phone' => SiteSetting::get('contact_phone'),
            'address_en' => SiteSetting::get('address_en'),
            'address_ar' => SiteSetting::get('address_ar'),
        ];

        $achievements = Achievement::latest()->get();

        return view('supervisor.content', compact('settings', 'achievements'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'school_name_en' => ['required', 'string', 'max:255'],
            'school_name_ar' => ['required', 'string', 'max:255'],
            'about_en' => ['nullable', 'string'],
            'about_ar' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'address_en' => ['nullable', 'string', 'max:500'],
            'address_ar' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($request->only([
            'school_name_en', 'school_name_ar', 'about_en', 'about_ar',
            'contact_email', 'contact_phone', 'address_en', 'address_ar',
        ]) as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return back()->with('success', 'Site settings updated.');
    }

    public function storeAchievement(Request $request)
    {
        $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        Achievement::create([
            ...$request->only(['title_en', 'title_ar', 'description_en', 'description_ar', 'icon']),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Achievement added.');
    }

    public function updateAchievement(Request $request, Achievement $achievement)
    {
        $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $achievement->update($request->only(['title_en', 'title_ar', 'description_en', 'description_ar', 'icon']));

        return back()->with('success', 'Achievement updated.');
    }

    public function destroyAchievement(Achievement $achievement)
    {
        $achievement->delete();

        return back()->with('success', 'Achievement deleted.');
    }
}
