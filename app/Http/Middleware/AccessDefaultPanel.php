<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessDefaultPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        if ($user->can('access_default_panel')) {
            return $next($request);
        }

        if ($user->can('access_blog_panel')) {
            return redirect('/blog');
        }

        if ($user->can('access_travel_panel')) {
            return redirect('/travel');
        }

        abort(403);
    }
}
