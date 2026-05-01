<?php

namespace App\Filament\Travel\Widgets;

use App\Models\Customer;
use App\Models\Trip;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TravelOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Customers', Customer::count())
                ->description('Total customers')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),
            Stat::make('Trips', Trip::count())
                ->description('Total trips booked')
                ->descriptionIcon('heroicon-o-map')
                ->color('success'),
            Stat::make('Revenue', number_format((float) Trip::sum('total_price'), 2))
                ->description('Sum of trip prices')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('warning'),
        ];
    }
}
