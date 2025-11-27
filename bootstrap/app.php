<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle API exceptions with standardized JSON responses
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'code' => 422,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Unauthenticated. Provide a valid Authorization: Bearer <token>',
                    'errors' => null,
                ], 401);
            }
        });

        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'code' => 403,
                    'message' => $e->getMessage() ?: 'Forbidden. You do not have permission to perform this action.',
                    'errors' => null,
                ], 403);
            }
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => 'Resource not found',
                    'errors' => null,
                ], 404);
            }
        });

        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                // Log the exception for debugging
                \Log::error('API Exception: ' . $e->getMessage(), [
                    'exception' => $e,
                    'request' => $request->all(),
                    'url' => $request->fullUrl(),
                ]);

                // Return generic error in production, detailed in development
                $message = config('app.debug') ? $e->getMessage() : 'Internal server error';
                
                return response()->json([
                    'success' => false,
                    'code' => 500,
                    'message' => $message,
                    'errors' => null,
                ], 500);
            }
        });
    })->create();
