<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\TeacherInvite;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherManagementController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->latest()->get();
        $invites = TeacherInvite::with('invitedBy')->latest()->get();

        return view('supervisor.teachers', compact('teachers', 'invites'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'emails' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $name = $request->input('name');
        $emails = array_filter(
            array_map('trim', explode("\n", $request->emails)),
            fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL),
        );

        if (empty($emails)) {
            return back()->withErrors(['emails' => 'No valid email addresses found.']);
        }

        $added = 0;
        foreach ($emails as $email) {
            if (TeacherInvite::where('email', $email)->exists()) {
                continue;
            }

            TeacherInvite::create([
                'email' => $email,
                'name' => $name ?: null,
                'invited_by' => auth()->id(),
                'status' => 'pending',
            ]);
            $added++;
        }

        return back()->with('success', "{$added} teacher email(s) added successfully.");
    }

    public function removeInvite(TeacherInvite $invite)
    {
        $invite->delete();

        return back()->with('success', 'Invitation removed.');
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
