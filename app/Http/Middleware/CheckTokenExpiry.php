<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $token = $user->currentAccessToken();

        if (! $token) {
            return response()->json(['message' => 'Token missing.'], 401);
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            // kill the token so it can’t be reused
            $token->delete();

            return response()->json(['message' => 'Token expired.'], 401);
        }

        return $next($request);
    }
}
