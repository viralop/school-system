<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        $theme = $request->query('theme', session('theme', 'dark'));

        if (in_array($theme, ['dark', 'light'])) {
            session(['theme' => $theme]);
        }

        return $next($request);
    }
}
