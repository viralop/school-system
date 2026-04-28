<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionManagementController extends Controller
{
    public function index()
    {
        $levels = Level::with('sections')->orderBy('order')->get();

        return view('supervisor.sections', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:10'],
            'level_id' => ['required', 'exists:levels,id'],
        ]);

        $exists = Section::where('name', $request->name)
            ->where('level_id', $request->level_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'This section already exists for this level.']);
        }

        Section::create([
            'name' => strtoupper($request->name),
            'level_id' => $request->level_id,
        ]);

        return back()->with('success', 'Section added successfully.');
    }

    public function destroy(Section $section)
    {
        if ($section->students()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete section with students. Move students first.']);
        }

        $section->delete();

        return back()->with('success', 'Section deleted.');
    }
}
