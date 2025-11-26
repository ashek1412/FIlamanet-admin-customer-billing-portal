<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\Dashboard;
use App\Filament\App\Pages\ResetPassword;
use App\Filament\Auth\Pages\Login;
use App\Filament\Resources\NotificationResource;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\EnsurePasswordIsReset;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Livewire\Auth\CustomLogin;
use Awcodes\FilamentGravatar\GravatarPlugin;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;


class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->colors([
                'primary' => Color::rgb('rgb(165, 42, 42)'),
            ])
            ->brandLogo(fn() => view('components.logo'))
            ->authGuard('web')
            ->login(Login::class)
            ->passwordReset()
            ->brandName('e-Bill Portal')
            ->breadcrumbs(false)
            ->profile()

            ->sidebarWidth('14rem')
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->resources([NotificationResource::class])
            ->pages([
                Dashboard::class,
                ResetPassword::class

            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                //    Widgets\AccountWidget::class

            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        hasAvatars: true
                    )
                    ->enableTwoFactorAuthentication(false),

                AuthUIEnhancerPlugin::make()
                    // ->formPanelBackgroundColor(Color::Zinc, '300')
                    ->formPanelWidth('30%')
                    ->emptyPanelBackgroundImageUrl(URL::to('/image/login.png')),
                EasyFooterPlugin::make()
                    ->withFooterPosition('footer')
                    ->withSentence('Copyright © ' . date('Y') . ' Air Alliance Limited.
                    All rights reserved. Air Alliance Limited is the authorized service contractor of
                    UPS and a concern of Bengal Airlift Limited')
                    ->footerEnabled(), // true by default


                FilamentPeekPlugin::make()
                    ->disablePluginStyles(),

                //                FilamentExceptionsPlugin::make(),

                GravatarPlugin::make(),
                // FilamentNordThemePlugin::make()

            ])->renderHook(

                'panels::body.end',
                fn(): string => Blade::render('
                <script>
                    document.addEventListener("livewire:init", () => {
                        Livewire.hook("request", ({ fail }) => {
                            fail(({ status, preventDefault }) => {
                                if (status === 419) {
                                    preventDefault();
                                    window.location.reload();
                                }
                            });
                        });
                    });
                </script>
            ')




            )

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsurePasswordIsReset::class,

            ])
            ->authMiddleware([
                AuthenticateUser::class,
                RedirectIfNotAdmin::class
            ]);
    }
}
