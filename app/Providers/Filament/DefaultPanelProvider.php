<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class DefaultPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->default()
            ->id('default')
            ->path('default')
            ->login()
            ->middleware([\App\Http\Middleware\AccessDefaultPanel::class])
            ->pages([Dashboard::class])
            ->discoverResources(in: app_path('Filament/Default/Resources'), for: 'App\Filament\Default\Resources')
            ->discoverPages(in: app_path('Filament/Default/Pages'), for: 'App\Filament\Default\Pages')
            ->discoverWidgets(in: app_path('Filament/Default/Widgets'), for: 'App\Filament\Default\Widgets')
            ->plugins([FilamentShieldPlugin::make()])
            ->widgets(
                [
                    AccountWidget::class,
                    FilamentInfoWidget::class,
                ]
            );
    }
}
