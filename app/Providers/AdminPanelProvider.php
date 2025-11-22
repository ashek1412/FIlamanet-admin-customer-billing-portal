<?php

namespace App\Providers;

use App\Filament\Pages\Dashboard;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\RedirectIfNotAdmin;
use Awcodes\FilamentGravatar\GravatarPlugin;
use Awcodes\FilamentGravatar\GravatarProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;


// See which login theme you want to use


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->login()

            ->profile()
            ->sidebarWidth('15rem')
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->spa()
            ->databaseNotifications()
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        hasAvatars: true
                    )
                    ->enableTwoFactorAuthentication(),

//                CuratorPlugin::make()
//                    ->label('Media')
//                    ->pluralLabel('Media Library')
//                    ->navigationIcon('heroicon-o-photo')
//                    ->navigationGroup('Media')
//                    ->navigationCountBadge(),

             //   FilamentJobsMonitorPlugin::make()
             //       ->navigationCountBadge()
            //        ->navigationGroup('Settings'),

                FilamentPeekPlugin::make()
                    ->disablePluginStyles(),

               // FilamentExceptionsPlugin::make(),


                GravatarPlugin::make(),
                FilamentAuthenticationLogPlugin::make(),
               // FilamentAlertsPlugin::make(),


            ])
            ->defaultAvatarProvider(GravatarProvider::class)
            ->favicon(asset('/favicon-32x32.png'))
            ->brandLogo(fn () => view('components.logo'))
            ->navigationGroups([
                'Collections',
                'Logs',
                'Settings',
            ])
            ->colors([
                'primary' =>Color::rgb('rgb(165, 42, 42)'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
               // Widgets\AccountWidget::class,
            ])
            ->middleware([
                'web',
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,

            ])
            ->authMiddleware([
                AuthenticateAdmin::class,
                RedirectIfNotAdmin::class
            ]);
    }


}
