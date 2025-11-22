<?php

use App\Filament\App\Pages\Dashboard;
use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\TokenMismatchException;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AdminPanelProvider::class,
        App\Providers\Filament\AppPanelProvider::class,
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.app' => \App\Http\Middleware\AuthenticateUser::class,
            'auth.admin' => \App\Http\Middleware\AuthenticateAdmin::class,
        ]);
        $middleware->appendToGroup('web', [
            TrustProxies::class,
            EncryptCookies::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ]);

     //   $middleware->redirectTo(fn() => Dashboard::getUrl());
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (TokenMismatchException $e, $request) {            // Redirect based on the request path

            if ($request->is('admin')) {
                return redirect()->route('login');
            }

            // Default to app panel login
            return redirect()->route('filament.app.auth.login');
        });
        $exceptions->reportable(
            fn(Throwable $e) => $exceptions->handler->shouldReport($e) &&
                FilamentExceptions::report($e)
        );
    })->create();
