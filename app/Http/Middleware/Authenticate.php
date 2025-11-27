<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Block redirects for API/JSON requests and let Laravel return 401.
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // Admin redirect vs user redirect
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        return route('login');
    }
}
