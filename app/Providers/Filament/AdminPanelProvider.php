<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use App\Filament\Pages\Auth\PasswordReset\ResetPassword;
use App\Support\Branding;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // El constructor GrapesJS es una página completa: se abre siempre con
        // navegación real, no con la navegación SPA de Filament, para que sus
        // scripts se ejecuten desde cero en cada visita.
        FilamentView::spaUrlExceptions(['*/constructor/*']);

        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->spa()
            ->globalSearch(false)
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->passwordReset(RequestPasswordReset::class, ResetPassword::class)
            ->profile()
            ->brandName(fn (): string => Branding::name())
            ->brandLogo(fn (): string => Branding::logoLightUrl())
            ->darkModeBrandLogo(fn (): string => Branding::logoDarkUrl())
            ->brandLogoHeight('3.5rem')
            ->favicon(fn (): string => Branding::faviconUrl())
            ->font('Poppins')
            ->colors([
                'primary' => Color::hex('#FF4C00'),
            ])
            ->darkMode()
            /*
             * El orden de los grupos del menú lateral es el de esta lista.
             * Configuración queda siempre al final, después de Contenido.
             */
            ->navigationGroups([
                'Contenido',
                'Configuración',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Configuración')
                    ->navigationSort(2),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): string => request()->routeIs('filament.admin.auth.*')
                    ? view('partials.footer')->render()
                    : view('filament.footer')->render(),
            );

        /*
         * El segundo factor se deja desactivado por ahora. Para exigirlo, define
         * SECURITY_MFA_REQUIRED=true en el entorno y todos los usuarios deberán
         * configurar TOTP al iniciar sesión.
         */
        if (config('security.mfa_required')) {
            $panel->multiFactorAuthentication([
                AppAuthentication::make(),
            ], isRequired: true);
        }

        return $panel;
    }
}
