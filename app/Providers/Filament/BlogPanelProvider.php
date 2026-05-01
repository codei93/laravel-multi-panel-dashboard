<?php

namespace App\Providers\Filament;

use Filament\Pages\Dashboard;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Panel;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class BlogPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('blog')
            ->path('blog')
            ->login()
            ->middleware([\App\Http\Middleware\AccessBlogPanel::class])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->pages([Dashboard::class])
            ->discoverResources(in: app_path('Filament/Blog/Resources'), for: 'App\Filament\Blog\Resources')
            ->discoverPages(in: app_path('Filament/Blog/Pages'), for: 'App\Filament\Blog\Pages')
            ->discoverWidgets(in: app_path('Filament/Blog/Widgets'), for: 'App\Filament\Blog\Widgets')
            ->widgets(
                [
                    AccountWidget::class,
                    FilamentInfoWidget::class,
                ]
            );
    }
}
