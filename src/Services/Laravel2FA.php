<?php

namespace MakelarisJR\Laravel2FA\Services;

use PragmaRX\Google2FA\Google2FA;

class Laravel2FA
{
    private Google2FA $google2fa;
    private Yubikey $yubikey2fa;

    public function __construct()
    {
        $this->google2fa  = new Google2FA();
        $this->yubikey2fa = new Yubikey(
            config('laravel2fa.yubikey.client_id'),
            config('laravel2fa.yubikey.secret_key'),
            config('laravel2fa.yubikey.api_server')
        );
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function verifyGoogleOTP(string $otp, string $secret): bool
    {
        return $this->google2fa->verifyKey(
            $secret,
            $otp,
            $this->google2fa->getWindow()
        );
    }

    public function verifyYubikeyOTP(string $otp): bool
    {
        return $this->yubikey2fa->verifyOtp($otp);
    }
}