<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->hasRequiredProfileInfo()) {
            return $next($request);
        }

        if ($request->routeIs('profile.complete.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        return redirect()->route('profile.complete.show');
    }
}
