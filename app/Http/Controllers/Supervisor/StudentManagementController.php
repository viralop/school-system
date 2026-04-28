<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $levels = Level::with('sections')->orderBy('order')->get();

        $query = Student::with(['level', 'section'])->latest();

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('student_number', 'like', "%{$request->search}%");
            });
        }

        $students = $query->paginate(20)->withQueryString();

        return view('supervisor.students', compact('students', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'level_id' => ['required', 'exists:levels,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $hasSections = Section::where('level_id', $request->level_id)->exists();

        if ($hasSections && ! $request->filled('section_id')) {
            return back()->withErrors(['section_id' => 'Please select a section for this level.']);
        }

        if ($request->filled('section_id')) {
            $section = Section::where('id', $request->section_id)
                ->where('level_id', $request->level_id)
                ->first();

            if (! $section) {
                return back()->withErrors(['section_id' => 'Section does not belong to the selected level.']);
            }
        }

        $level = Level::find($request->level_id);

        try {
            DB::beginTransaction();

            $student = Student::create([
                'student_number' => $level->nextStudentNumber(),
                'name' => $request->name,
                'parent_phone' => $request->parent_phone,
                'level_id' => $request->level_id,
                'section_id' => $request->filled('section_id') ? $request->section_id : null,
                'status' => 'active',
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['name' => 'Failed to create student. Please try again.'])->withInput();
        }

        return back()->with('success', "Student created. Number: {$student->student_number}");
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'level_id' => ['required', 'exists:levels,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $hasSections = Section::where('level_id', $request->level_id)->exists();

        if ($hasSections && ! $request->filled('section_id')) {
            return back()->withErrors(['section_id' => 'Please select a section for this level.']);
        }

        if ($request->filled('section_id')) {
            $section = Section::where('id', $request->section_id)
                ->where('level_id', $request->level_id)
                ->first();

            if (! $section) {
                return back()->withErrors(['section_id' => 'Section does not belong to the selected level.']);
            }
        }

        $student->update($request->only(['name', 'parent_phone', 'level_id', 'section_id']));

        return back()->with('success', "Student {$student->name} updated.");
    }

    public function freeze(Student $student)
    {
        $student->freeze();

        return back()->with('success', "Student {$student->name} has been frozen.");
    }

    public function unfreeze(Student $student)
    {
        $student->unfreeze();

        return back()->with('success', "Student {$student->name} has been unfrozen.");
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return back()->with('success', "Student {$student->name} has been deleted.");
    }
}
