<?php

namespace App\Http\Middleware;
    use Closure;
    use Filament\Facades\Filament;
    use Filament\Pages\Dashboard;
    use Filament\PanelRegistry;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()) {
            $panel = Filament::getCurrentPanel()->getId();

            if(Auth::user()->is_admin==1)
                Filament::setCurrentPanel(app(PanelRegistry::class)->panels['admin']);
            else
                Filament::setCurrentPanel(app(PanelRegistry::class)->panels['app']);
        }


        return $next($request);

    }
}
