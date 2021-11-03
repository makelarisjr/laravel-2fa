<?php

namespace MakelarisJR\Laravel2FA\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use MakelarisJR\Laravel2FA\Events\AuthorizationFailed;
use MakelarisJR\Laravel2FA\Events\UserAuthorized;
use MakelarisJR\Laravel2FA\Http\Requests\Verify2FARequest;

class Verify2FAController extends Controller
{
    public function __invoke(Verify2FARequest $request): RedirectResponse
    {
        $user = $request->user();
        $otp  = $request->input('otp');
        $remember = $request->filled('remember_device');

        if($user->verifyOtp($otp))
        {
            if (config('laravel2fa.remember_cookie.enabled') && $remember)
            {
                $token = $user->generateOtpRememberToken();
                Cookie::queue(
                    config('laravel2fa.remember_cookie.name'),
                    encrypt($token),
                    config('laravel2fa.remember_cookie.lifetime')
                );
            }

            Session::put('2fa_passed', true);

            broadcast(new UserAuthorized($user, $remember));

            return redirect(Session::get(
                '2fa_redirect_to',
                config('laravel2fa.default_redirect', '/dashboard')
            ));
        }

        broadcast(new AuthorizationFailed($user));

        return redirect()
            ->back()
            ->withErrors(['otp' => 'Invalid OTP Code']);
    }
}