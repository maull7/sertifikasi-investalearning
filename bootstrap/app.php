<?php

use App\Http\Middleware\ActivationUserMiddleware;
use App\Http\Middleware\CheckIsLogin;
use App\Http\Middleware\EnsureProfileComplete;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsAdminOnly;
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
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'admin-only' => EnsureUserIsAdminOnly::class,
            'akun-active' => ActivationUserMiddleware::class,
            'profile-complete' => EnsureProfileComplete::class,
            'check-login' => CheckIsLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->context(fn () => [
            'url' => request()?->fullUrl(),
            'method' => request()?->method(),
            'user_id' => auth()->id(),
        ]);
    })->create();
