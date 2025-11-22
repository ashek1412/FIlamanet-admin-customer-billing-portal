<?php
namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate;

class AuthenticateAdmin extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson() ? null : route('filament.admin.auth.login');
    }
}
