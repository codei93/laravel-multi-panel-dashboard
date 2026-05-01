<?php

namespace App\Providers\Filament;

use App\Filament\Travel\Widgets\TravelOverview;
use Filament\Pages\Dashboard;
use Filament\Panel;

class TravelPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('travel')
            ->path('travel')
            ->login()
            ->middleware([\App\Http\Middleware\AccessTravelPanel::class])
            ->pages([Dashboard::class])
            ->discoverResources(in: app_path('Filament/Travel/Resources'), for: 'App\Filament\Travel\Resources')
            ->discoverPages(in: app_path('Filament/Travel/Pages'), for: 'App\Filament\Travel\Pages')
            ->discoverWidgets(in: app_path('Filament/Travel/Widgets'), for: 'App\Filament\Travel\Widgets')
            ->widgets([TravelOverview::class]);
    }
}
