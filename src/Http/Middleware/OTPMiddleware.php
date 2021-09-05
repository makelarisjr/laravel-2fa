<?php

namespace MakelarisJR\Laravel2FA\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class OTPMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->has2FAEnabled())
        {
            if ($value = Cookie::get(config('laravel2fa.remember_cookie.name')))
            {
                $token = $user->otpRememberTokens()
                    ->where('token', decrypt($value))
                    ->first();

                if ($token && !$token->hasExpired())
                {
                    return $next($request);
                }
            }

            if (Session::has('2fa_passed'))
            {
                return $next($request);
            }

            Session::put('2fa_redirect_to', $request->path());

            return redirect()
                ->route('verify2fa');
        }

        return $next($request);
    }
}