<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (! $request->expectsJson()) {
            return response()->json(['message' => 'Accept header must be set to application/json'], 400);
        }

        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}