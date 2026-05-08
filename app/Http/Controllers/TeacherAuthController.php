<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.teacher-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'teacher_id' => ['required', 'string'],
        ]);

        $user = User::where('teacher_id', $request->teacher_id)->where('role', 'teacher')->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Teacher ID not found.',
            ]);
        }

        if ($user->isFrozen()) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Your account has been frozen. Contact the supervisor.',
            ]);
        }

        session([
            'teacher_id' => $user->teacher_id,
            'teacher_name' => $user->name,
            'teacher_user_id' => $user->id,
        ]);

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login');
    }
}
