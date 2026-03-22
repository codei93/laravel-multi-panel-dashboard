<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
        $items = [];
        //$user = auth()->user();

        //if (! $user) {
        //  return $items;
        //}

        //if ($user->can('access_default_panel')) {
        $items[] = Action::make('admin_panel')->label('Admin Panel')->url('/default')->icon('heroicon-o-home');
        //}

        //if ($user->can('access_blog_panel')) {
        $items[] = Action::make('blog_panel')->label('Blog Panel')->url('/blog')->icon('heroicon-o-document-text');
        //}

        //if ($user->can('access_travel_panel')) {
        $items[] = Action::make('travel_panel')->label('Travel Panel')->url('/travel')->icon('heroicon-o-globe-alt');
        //}

        return $items;
    }
}
