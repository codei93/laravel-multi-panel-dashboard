<?php

namespace App\Providers\Filament;

use App\Filament\Blog\Widgets\BlogOverview;
use Filament\Pages\Dashboard;
use Filament\Panel;

class BlogPanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('blog')
            ->path('blog')
            ->login()
            ->middleware([\App\Http\Middleware\AccessBlogPanel::class])
            ->pages([Dashboard::class])
            ->discoverResources(in: app_path('Filament/Blog/Resources'), for: 'App\Filament\Blog\Resources')
            ->discoverPages(in: app_path('Filament/Blog/Pages'), for: 'App\Filament\Blog\Pages')
            ->discoverWidgets(in: app_path('Filament/Blog/Widgets'), for: 'App\Filament\Blog\Widgets')
            ->widgets([BlogOverview::class]);
    }
}
