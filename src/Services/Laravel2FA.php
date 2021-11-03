<?php

namespace MakelarisJR\Laravel2FA\Services;

use MakelarisJR\Laravel2FA\Exception\GoogleOTPDisabledException;
use MakelarisJR\Laravel2FA\Exception\YubikeyOTPDisabledException;
use PragmaRX\Google2FA\Google2FA;

class Laravel2FA
{
    private Google2FA $google2fa;
    private Yubikey $yubikey2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();

        if (config('laravel2fa.yubikey.enabled')) {
            $this->yubikey2fa = new Yubikey(
                config('laravel2fa.yubikey.client_id'),
                config('laravel2fa.yubikey.secret_key'),
                config('laravel2fa.yubikey.api_server')
            );
        }
    }

    /**
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * @return bool|int
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     * @throws \MakelarisJR\Laravel2FA\Exception\GoogleOTPDisabledException
     */
    public function verifyGoogleOTP(string $otp, string $secret)
    {
        if (!config('laravel2fa.google.enabled')) {
            throw new GoogleOTPDisabledException('Google OTP is disabled.');
        }

        return $this->google2fa->verifyKey(
            $secret,
            $otp,
            $this->google2fa->getWindow()
        );
    }

    /**
     * @throws \MakelarisJR\Laravel2FA\Exception\YubikeyOTPDisabledException
     */
    public function verifyYubikeyOTP(string $otp): bool
    {
        if (!config('laravel2fa.yubikey.enabled')) {
            throw new YubikeyOTPDisabledException('Yubikey OTP is disabled.');
        }

        return $this->yubikey2fa->verifyOtp($otp);
    }
}
