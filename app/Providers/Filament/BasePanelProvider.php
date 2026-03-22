<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Filament\Http\Middleware\{Authenticate, AuthenticateSession, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Pages\Dashboard;
use Filament\Widgets\{AccountWidget, FilamentInfoWidget};
use Filament\Support\Colors\Color;
use Filament\Actions\Action;


abstract class BasePanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn(): string => Blade::render("@vite(['resources/css/app.css', 'resources/js/app.js'])"),
        );
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->colors(['primary' => Color::Amber])
            ->userMenuItems($this->userMenuItems())
            ->middleware($this->middleware())
            ->authMiddleware([Authenticate::class]);
    }

    protected function middleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];
    }

    protected function userMenuItems(): array
    {
        return [
            Action::make('admin_panel')->label('Admin Panel')->url('/default')->icon('heroicon-o-home'),
            Action::make('blog_panel')->label('Blog Panel')->url('/blog')->icon('heroicon-o-document-text'),
            Action::make('travel_panel')->label('Travel Panel')->url('/travel')->icon('heroicon-o-globe-alt'),
        ];
    }
}