<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstructorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Unauthenticated',
                    'errors' => null,
                ], 401);
            }
            abort(401);
        }

        // Check if user is instructor
        if ($user->role !== 'instructor') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'code' => 403,
                    'message' => 'You must be an approved instructor to access this resource.',
                    'errors' => null,
                ], 403);
            }
            abort(403, 'You must be an approved instructor to access this resource.');
        }

        // Check if user has an approved application
        $application = $user->instructorApplication;
        if (!$application || $application->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'code' => 403,
                    'message' => 'Your instructor application is pending approval or has been rejected.',
                    'errors' => null,
                ], 403);
            }
            abort(403, 'Your instructor application is pending approval or has been rejected.');
        }

        return $next($request);
    }
}
