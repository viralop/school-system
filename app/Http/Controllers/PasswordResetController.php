<?php

namespace App\Http\Controllers;

use App\Models\TeacherInvite;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function __construct(
        private OtpService $otpService,
    ) {}

    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->where('role', 'teacher')->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'No teacher account found with this email.',
            ]);
        }

        $this->otpService->generate($user, 'password_reset', $user->email, $request->ip());

        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.reset.otp');
    }

    public function showOtpForm()
    {
        if (! session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.forgot-password-otp', [
            'email' => session('password_reset_email'),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->where('role', 'teacher')->first();

        if (! $user) {
            return redirect()->route('password.request');
        }

        $result = $this->otpService->verify($user, 'password_reset', $request->code);

        if (! $result['success']) {
            throw ValidationException::withMessages(['code' => $result['reason']]);
        }

        session(['password_reset_verified' => true]);

        return redirect()->route('password.reset.new');
    }

    public function showNewPasswordForm()
    {
        if (! session('password_reset_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.forgot-password-new', [
            'email' => session('password_reset_email'),
        ]);
    }

    public function setNewPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->where('role', 'teacher')->first();

        if (! $user) {
            return redirect()->route('password.request');
        }

        $user->update(['password' => Hash::make($request->password)]);

        session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect()->route('teacher.login')
            ->with('status', 'Password reset successfully. Please log in.');
    }

    public function resendOtp(Request $request)
    {
        $email = session('password_reset_email');
        $user = User::where('email', $email)->where('role', 'teacher')->first();

        if (! $user) {
            return back()->withErrors(['code' => 'Session expired.']);
        }

        $canResend = $this->otpService->canResend($user, 'password_reset');

        if (! $canResend['can_resend']) {
            return back()->withErrors([
                'code' => "Please wait {$canResend['remaining_seconds']} seconds.",
            ]);
        }

        $this->otpService->generate($user, 'password_reset', $user->email, $request->ip());
        $this->otpService->setResendCooldown($user, 'password_reset');

        return back()->with('status', 'A new OTP has been sent.');
    }
}
