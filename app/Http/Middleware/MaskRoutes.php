<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaskRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
{
    $alias = trim($request->path(), '/');

    // ignore API, assets
    if ($request->is('api/*') || $request->is('*.*')) {
        return $next($request);
    }

    // Already normal Vue route?
    if (preg_match('/^[a-z0-9-]+$/i', $alias) && strlen($alias) < 40) {
        return $next($request);
    }

    // alias → original mapping
    if (session()->has("alias.$alias")) {
        $original = session("alias.$alias");
        return redirect($original);
    }

    return $next($request);
}

}
