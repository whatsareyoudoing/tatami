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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        // User yang sudah login lalu membuka /login: admin/editor ke dashboard, customer ke beranda
        $middleware->redirectUsersTo(fn (\Illuminate\Http\Request $request) => $request->user()->can('access_admin')
            ? route('dashboard')
            : route('home'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
