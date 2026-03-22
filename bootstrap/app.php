<?php

use App\Http\Middleware\AccessBlogPanel;
use App\Http\Middleware\AccessDefaultPanel;
use App\Http\Middleware\AccessTravelPanel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'access_default_panel' => AccessDefaultPanel::class,
            'access_blog_panel' => AccessBlogPanel::class,
            'access_travel_panel' => AccessTravelPanel::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
