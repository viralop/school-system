<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherManagementController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->latest()->get();

        return view('supervisor.teachers', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'teacher_ids' => ['required', 'string'],
        ]);

        $name = $request->input('name');
        $teacherIds = array_filter(
            array_map('trim', explode("\n", $request->teacher_ids)),
            fn($id) => strlen($id) >= 2,
        );

        if (empty($teacherIds)) {
            return back()->withErrors(['teacher_ids' => 'No valid teacher IDs found.']);
        }

        $added = 0;
        foreach ($teacherIds as $id) {
            if (User::where('teacher_id', $id)->exists()) {
                continue;
            }

            User::create([
                'name' => $name,
                'email' => $id . '@teacher.local',
                'password' => '',
                'role' => 'teacher',
                'status' => 'active',
                'teacher_id' => $id,
            ]);
            $added++;
        }

        return back()->with('success', "{$added} teacher(s) created successfully.");
    }

    public function freeze(User $teacher)
    {
        if (! $teacher->isTeacher()) {
            return back()->withErrors(['error' => 'User is not a teacher.']);
        }

        $teacher->freeze();

        return back()->with('success', "Teacher {$teacher->name} has been frozen.");
    }

    public function unfreeze(User $teacher)
    {
        if (! $teacher->isTeacher()) {
            return back()->withErrors(['error' => 'User is not a teacher.']);
        }

        $teacher->unfreeze();

        return back()->with('success', "Teacher {$teacher->name} has been unfrozen.");
    }

    public function destroy(User $teacher)
    {
        if (! $teacher->isTeacher()) {
            return back()->withErrors(['error' => 'User is not a teacher.']);
        }

        $teacher->delete();

        return back()->with('success', "Teacher {$teacher->name} has been deleted.");
    }
}
