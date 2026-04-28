<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotFrozen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isFrozen()) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('teacher.login')
                ->withErrors(['email' => 'Your account has been frozen. Contact the supervisor.']);
        }

        return $next($request);
    }
}
