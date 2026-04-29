<?php

namespace App\Http\Controllers;

use App\Models\TeacherInvite;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
{
    public function __construct(
        private OtpService $otpService,
    ) {}

    public function showSignupForm()
    {
        return view('auth.teacher-signup');
    }

    public function signupStep1(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (! TeacherInvite::isEmailApproved($request->email)) {
            throw ValidationException::withMessages([
                'email' => 'This email is not pre-approved for teacher registration.',
            ]);
        }

        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            throw ValidationException::withMessages([
                'email' => 'An account with this email already exists.',
            ]);
        }

        session([
            'teacher_signup_email' => $request->email,
            'teacher_signup_name' => TeacherInvite::where('email', $request->email)->value('name'),
            'teacher_signup_step' => 3,
        ]);

        return redirect()->route('teacher.signup.password');
    }

    public function showSignupOtpForm()
    {
        if (! session('teacher_signup_email') || session('teacher_signup_step') < 2) {
            return redirect()->route('teacher.signup');
        }

        return view('auth.teacher-signup-otp', [
            'email' => session('teacher_signup_email'),
        ]);
    }

    public function signupVerifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $email = session('teacher_signup_email');

        $tempUser = new User(['email' => $email]);
        $result = $this->otpService->verify($tempUser, 'teacher_signup', $request->code);

        if (! $result['success']) {
            throw ValidationException::withMessages([
                'code' => $result['reason'],
            ]);
        }

        session(['teacher_signup_step' => 3]);

        return redirect()->route('teacher.signup.password');
    }

    public function showSetPasswordForm()
    {
        if (session('teacher_signup_step') < 3) {
            return redirect()->route('teacher.signup');
        }

        return view('auth.teacher-set-password', [
            'email' => session('teacher_signup_email'),
            'prefilled_name' => session('teacher_signup_name'),
        ]);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = session('teacher_signup_email');

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'status' => 'active',
        ]);

        TeacherInvite::markUsed($email);

        session()->forget(['teacher_signup_email', 'teacher_signup_step']);

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
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->where('role', 'teacher')->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        if ($user->isFrozen()) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been frozen. Contact the supervisor.',
            ]);
        }

        session()->forget('teacher_login_id');

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    public function showLoginOtpForm()
    {
        if (! session('teacher_login_id')) {
            return redirect()->route('teacher.login');
        }

        $user = User::find(session('teacher_login_id'));

        return view('auth.teacher-login-otp', [
            'email' => $user->email,
        ]);
    }

    public function loginVerifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = User::find(session('teacher_login_id'));

        if (! $user) {
            return redirect()->route('teacher.login');
        }

        $result = $this->otpService->verify($user, 'teacher_login', $request->code);

        if (! $result['success']) {
            throw ValidationException::withMessages([
                'code' => $result['reason'],
            ]);
        }

        session()->forget('teacher_login_id');

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'purpose' => ['required', 'string', 'in:teacher_signup,teacher_login'],
        ]);

        if ($request->purpose === 'teacher_signup') {
            $email = session('teacher_signup_email');
            $authenticatable = new User(['email' => $email]);
        } else {
            $user = User::find(session('teacher_login_id'));
            if (! $user) {
                return back()->withErrors(['code' => 'Session expired.']);
            }
            $email = $user->email;
            $authenticatable = $user;
        }

        $canResend = $this->otpService->canResend($authenticatable, $request->purpose);

        if (! $canResend['can_resend']) {
            return back()->withErrors([
                'code' => "Please wait {$canResend['remaining_seconds']} seconds before requesting a new OTP.",
            ]);
        }

        $this->otpService->generate($authenticatable, $request->purpose, $email, $request->ip());
        $this->otpService->setResendCooldown($authenticatable, $request->purpose);

        return back()->with('status', 'A new OTP has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login');
    }
}
