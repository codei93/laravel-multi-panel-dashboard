<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Pages\Dashboard;
use Filament\Widgets\{AccountWidget, FilamentInfoWidget};

class TravelPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('travel')
            ->path('travel')
            ->login()
            ->pages([Dashboard::class])
            ->discoverResources(in: app_path('Filament/Travel/Resources'), for: 'App\Filament\Travel\Resources')
            ->discoverPages(in: app_path('Filament/Travel/Pages'), for: 'App\Filament\Travel\Pages')
            ->discoverWidgets(in: app_path('Filament/Travel/Widgets'), for: 'App\Filament\Travel\Widgets')
            ->widgets(
                [
                    AccountWidget::class,
                    FilamentInfoWidget::class,
                ]
            );
    }
}