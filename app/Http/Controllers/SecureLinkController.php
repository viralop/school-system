<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\SecureLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecureLinkController extends Controller
{
    public function show(Request $request)
    {
        $secureLink = $request->attributes->get('secureLink');

        if ($secureLink->purpose === 'supervisor_registration') {
            return view('auth.supervisor-register', [
                'secureLink' => $secureLink,
            ]);
        }

        abort(404);
    }

    public function register(Request $request)
    {
        $secureLink = $request->attributes->get('secureLink');

        if ($secureLink->purpose !== 'supervisor_registration') {
            abort(403);
        }

        if ($secureLink->isExhausted()) {
            AccessLog::logAttempt(
                $request,
                'register_link_exhausted',
                false,
                secureLinkId: $secureLink->id,
                failureReason: 'Link usage limit reached',
            );

            return redirect()->route('login')
                ->withErrors(['link' => 'This registration link has already been used.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'supervisor',
        ]);

        $secureLink->consume();

        AccessLog::logAttempt(
            $request,
            'supervisor_registered',
            true,
            secureLinkId: $secureLink->id,
            userId: $user->id,
        );

        auth()->login($user);

        return redirect()->route('supervisor.dashboard')
            ->with('success', 'Supervisor account created successfully!');
    }
}
