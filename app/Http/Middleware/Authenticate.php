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
		// For API and JSON requests, do not redirect. Let Laravel return a 401 JSON.
		if ($request->expectsJson() || $request->is('api/*')) {
			return null;
		}

		return $request->is('admin*') ? route('admin.login') : route('login');
	}
}
