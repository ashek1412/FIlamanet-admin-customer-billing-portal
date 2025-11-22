<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class EnsurePasswordIsReset
{
    // Routes that should be accessible without password reset
    protected array $excludedRoutes = [
        'filament.*.auth.logout',
        'filament.*.pages.reset-password',
        'livewire.*',
        'sanctum.*',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Skip if user is not authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip if user doesn't need password reset
        if (!$user->needsPasswordReset()) {
            return $next($request);
        }

        // Check if current route should be excluded
        $currentRoute = $request->route()->getName();

        foreach ($this->excludedRoutes as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                return $next($request);
            }
        }

        // Get the current panel ID
        $panel = Filament::getCurrentPanel();
        $panelId = $panel ? $panel->getId() : 'admin';

        // Build the correct route name
        $resetRoute = "filament.{$panelId}.pages.reset-password";

        // Check if the route exists and redirect
        if (app('router')->has($resetRoute)) {
            return redirect()->route($resetRoute);
        }

        // Fallback to URL if route doesn't exist
        return redirect("/{$panelId}/reset-password");
    }
}
