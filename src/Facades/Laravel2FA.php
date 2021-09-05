<?php

namespace MakelarisJR\Laravel2FA\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Laravel2FA
 * @package MakelarisJR\Laravel2FA\Facades
 * @method static string generateSecretKey()
 * @method static bool verifyGoogleOTP(string $otp, string $secret)
 * @method static bool verifyYubikeyOTP(string $otp)
 * @see \MakelarisJR\Laravel2FA\Services\Laravel2FA
 */
class Laravel2FA extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel2fa';
    }
}