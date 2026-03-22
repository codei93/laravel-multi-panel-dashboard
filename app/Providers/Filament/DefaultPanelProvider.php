<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Pages\Dashboard;
use Filament\Widgets\{AccountWidget, FilamentInfoWidget};
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class DefaultPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->default()
            ->id('default')
            ->path('default')
            ->login()
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