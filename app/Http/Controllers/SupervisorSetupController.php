<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SupervisorSetupController extends Controller
{
    public function show(Request $request)
    {
        if (User::where('role', 'supervisor')->exists()) {
            return redirect()->route('login');
        }

        return view('auth.setup');
    }

    public function store(Request $request)
    {
        if (User::where('role', 'supervisor')->exists()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'A supervisor account already exists.']);
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
            'status' => 'active',
        ]);

        auth()->login($user);

        return redirect()->route('supervisor.dashboard')
            ->with('success', 'Supervisor account created successfully!');
    }
}
