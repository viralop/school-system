<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SetTheme::class,
        ]);

        $middleware->alias([
            'secure.link' => \App\Http\Middleware\SecureLinkMiddleware::class,
            'not.frozen' => \App\Http\Middleware\EnsureUserIsNotFrozen::class,
            'student.auth' => \App\Http\Middleware\EnsureStudentIsAuthenticated::class,
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
