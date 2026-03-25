<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        if ((bool) Auth::user()->is_admin) {
            return $next($request);
        }

        $userEmail = Str::lower((string) Auth::user()->email);
        $allowedEmails = array_map('strtolower', config('admin.emails', []));

        if (! in_array($userEmail, $allowedEmails, true)) {
            abort(403);
        }

        return $next($request);
    }
}
