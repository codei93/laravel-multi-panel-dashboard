<?php

namespace App\Filament\Blog\Widgets;

use App\Models\Category;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Posts', Post::count())
                ->description('Total posts')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),
            Stat::make('Categories', Category::count())
                ->description('Available categories')
                ->descriptionIcon('heroicon-o-tag')
                ->color('success'),
            Stat::make('Posts this week', Post::where('created_at', '>=', now()->subWeek())->count())
                ->description('Created in last 7 days')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('warning'),
        ];
    }
}
