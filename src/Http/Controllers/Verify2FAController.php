<?php

namespace MakelarisJR\Laravel2FA\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class Verify2FAController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();
        $otp = $request->input('otp');

        if ($user->verifyOtp($otp)) {
            if (config('laravel2fa.remember_cookie.enabled')) {
                $token = $user->generateOtpRememberToken();
                Cookie::queue(
                    config('laravel2fa.remember_cookie.name'),
                    encrypt($token),
                    config('laravel2fa.remember_cookie.lifetime')
                );
            }

            Session::put('2fa_passed', true);

            return redirect(Session::get(
                '2fa_redirect_to',
                config('laravel2fa.default_redirect', '/dashboard')
            ));
        }

        return redirect()
            ->back()
            ->withErrors(['otp' => 'Invalid OTP Code']);
    }
}
