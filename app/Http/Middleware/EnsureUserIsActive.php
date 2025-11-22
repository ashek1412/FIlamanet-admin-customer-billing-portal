<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && ! auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('filament.app.auth.login')->withErrors([
                'email' => 'Your account is inactive.',
            ]);
        }

        return $next($request);
    }
}
