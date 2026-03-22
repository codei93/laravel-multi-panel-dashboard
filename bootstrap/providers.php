<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\BlogPanelProvider;
use App\Providers\Filament\DefaultPanelProvider;
use App\Providers\Filament\TravelPanelProvider;

return [
    AppServiceProvider::class,
    BlogPanelProvider::class,
    DefaultPanelProvider::class,
    TravelPanelProvider::class,
];
