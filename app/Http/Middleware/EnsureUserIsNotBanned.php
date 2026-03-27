<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
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

        if (! $user->banned_until || now()->greaterThanOrEqualTo($user->banned_until)) {
            return $next($request);
        }

        if ($request->routeIs('logout') || $request->routeIs('admin.logout')) {
            return $next($request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('ban_popup', 'This user has been banned for '.($user->ban_duration_hours ?: $user->banned_until?->diffInHours($user->ban_started_at ?: now()) ?: 0).' hours, see email for reason.');
    }
}
