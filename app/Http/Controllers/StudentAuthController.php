<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'student_number' => ['required', 'string'],
        ]);

        $student = Student::where('student_number', $request->student_number)->first();

        if (! $student) {
            throw ValidationException::withMessages([
                'student_number' => 'Student number not found.',
            ]);
        }

        if ($student->isFrozen()) {
            throw ValidationException::withMessages([
                'student_number' => 'Your account has been frozen. Contact the school.',
            ]);
        }

        session([
            'student_id' => $student->id,
            'student_number' => $student->student_number,
        ]);

        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['student_id', 'student_number']);

        return redirect()->route('student.login');
    }
}
