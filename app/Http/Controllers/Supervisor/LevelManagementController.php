<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelManagementController extends Controller
{
    public function index()
    {
        $levels = Level::withCount(['students', 'subjects', 'sections'])->orderBy('order')->get();

        return view('supervisor.levels', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        Level::create([
            'name' => $request->name_en,
            'name_ar' => $request->name_ar,
            'order' => $request->order,
        ]);

        return back()->with('success', __('messages.Level created successfully.'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        $level->update([
            'name' => $request->name_en,
            'name_ar' => $request->name_ar,
            'order' => $request->order,
        ]);

        return back()->with('success', __('messages.Level updated successfully.'));
    }

    public function destroy(Level $level)
    {
        if ($level->students()->exists()) {
            return back()->withErrors(['error' => __('messages.Cannot delete level with students.')]);
        }

        if ($level->subjects()->exists()) {
            return back()->withErrors(['error' => __('messages.Cannot delete level with subjects.')]);
        }

        if ($level->sections()->exists()) {
            return back()->withErrors(['error' => __('messages.Cannot delete level with sections.')]);
        }

        $level->delete();

        return back()->with('success', __('messages.Level deleted successfully.'));
    }
}
