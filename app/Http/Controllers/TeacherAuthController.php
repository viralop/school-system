<?php

namespace App\Http\Controllers;

use App\Models\TeacherInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
{
    public function showSignupForm()
    {
        return view('auth.teacher-signup');
    }

    public function signupStep1(Request $request)
    {
        $request->validate([
            'teacher_id' => ['required', 'string'],
        ]);

        if (! TeacherInvite::isTeacherIdApproved($request->teacher_id)) {
            throw ValidationException::withMessages([
                'teacher_id' => 'This teacher ID is not recognized.',
            ]);
        }

        $invite = TeacherInvite::where('teacher_id', $request->teacher_id)
            ->where('status', 'pending')
            ->first();

        session([
            'teacher_signup_id' => $request->teacher_id,
            'teacher_signup_name' => $invite->name,
        ]);

        return redirect()->route('teacher.signup.password');
    }

    public function showSetPasswordForm()
    {
        if (! session('teacher_signup_id')) {
            return redirect()->route('teacher.signup');
        }

        return view('auth.teacher-set-password', [
            'teacher_id' => session('teacher_signup_id'),
            'prefilled_name' => session('teacher_signup_name'),
        ]);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $teacherId = session('teacher_signup_id');

        $user = User::create([
            'name' => $request->name,
            'email' => $teacherId . '@teacher.local',
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'status' => 'active',
            'teacher_id' => $teacherId,
        ]);

        TeacherInvite::markUsed($teacherId);

        session()->forget(['teacher_signup_id', 'teacher_signup_name']);

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.teacher-login');
    }

    public function loginStep1(Request $request)
    {
        $request->validate([
            'teacher_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('teacher_id', $request->teacher_id)->where('role', 'teacher')->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Invalid credentials.',
            ]);
        }

        if ($user->isFrozen()) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Your account has been frozen. Contact the supervisor.',
            ]);
        }

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
