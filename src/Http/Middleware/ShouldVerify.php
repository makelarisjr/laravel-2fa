<?php

namespace MakelarisJR\Laravel2FA\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ShouldVerify
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user->has2FAEnabled() || Session::has('2fa_passed'))
        {
            return redirect(
                config('laravel2fa.default_redirect', '/dashboard')
            );
        }

        return $next($request);
    }
}