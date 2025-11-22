<?php
namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate;

class AuthenticateUser extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson() ? null : route('filament.app.auth.login');
    }
}
