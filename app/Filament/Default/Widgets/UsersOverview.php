<?php

namespace App\Filament\Default\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class UsersOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count())
                ->description('Total registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Roles', Role::count())
                ->description('Defined roles')
                ->descriptionIcon('heroicon-o-shield-check')
                ->color('success'),
            Stat::make('Super Admins', Role::where('name', 'super_admin')->first()?->users()->count() ?? 0)
                ->description('Users with full access')
                ->descriptionIcon('heroicon-o-key')
                ->color('warning'),
        ];
    }
}
