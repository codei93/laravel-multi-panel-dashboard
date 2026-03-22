<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DefaultPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => Blade::render("@vite(['resources/css/app.css', 'resources/js/app.js'])"),
        );
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('default')
            ->path('default')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Admin Panel')
                    ->url('/default')
                    ->icon('heroicon-o-home'),

                MenuItem::make()
                    ->label('Blog Panel')
                    ->url('/blog')
                    ->icon('heroicon-o-document-text'),

                MenuItem::make()
                    ->label('Travel Panel')
                    ->url('/travel')
                    ->icon('heroicon-o-globe-alt'),

                'profile' => MenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-user'),

                'logout' => MenuItem::make()
                    ->label('Sign Out'),
            ])
            ->discoverResources(in: app_path('Filament/Default/Resources'), for: 'App\Filament\Default\Resources')
            ->discoverPages(in: app_path('Filament/Default/Pages'), for: 'App\Filament\Default\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Default/Widgets'), for: 'App\Filament\Default\Widgets')
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
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
