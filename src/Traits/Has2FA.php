<?php

namespace MakelarisJR\Laravel2FA\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MakelarisJR\Laravel2FA\Actions\GenerateBackupCodes;
use MakelarisJR\Laravel2FA\Actions\GenerateGoogleQRCode;
use MakelarisJR\Laravel2FA\Events\BackupCodeUsed;
use MakelarisJR\Laravel2FA\Facades\Laravel2FA;
use MakelarisJR\Laravel2FA\Models\OtpBackupCode;
use MakelarisJR\Laravel2FA\Models\OtpDevice;
use MakelarisJR\Laravel2FA\Models\OtpRememberToken;

trait Has2FA
{
    public function otpDevices(): MorphMany
    {
        return $this->morphMany(OtpDevice::class, 'model');
    }

    public function otpBackupCodes(): MorphMany
    {
        return $this->morphMany(OtpBackupCode::class, 'model');
    }

    public function otpRememberTokens(): MorphMany
    {
        return $this->morphMany(OtpRememberToken::class, 'model');
    }

    public function verifyOtp(string $otp): bool
    {
        if ($code = $this->otpBackupCodes()->where('code', $otp)->whereNull('used_at')->first())
        {
            $code->update([
                'used_at' => Carbon::now()
            ]);

            event(new BackupCodeUsed($this, $otp));

            return true;
        }

        if (Str::length($otp) === 44)
        {
            return $this->otpDevices()
                    ->where('type', OtpDevice::TYPE_YUBIKEY)
                    ->where('otp_secret', Str::substr($otp, 0, 12))
                    ->exists()
                && config('laravel2fa.yubikey.enabled')
                && Laravel2FA::verifyYubikeyOTP($otp);
        }

        if (config('laravel2fa.google.enabled'))
        {
            $secrets = $this->otpDevices()
                ->where('type', OtpDevice::TYPE_GOOGLE)
                ->pluck('otp_secret');

            foreach ($secrets as $secret)
            {
                if (Laravel2FA::verifyGoogleOTP($otp, $secret))
                {
                    return true;
                }
            }
        }

        return false;
    }

    public function addDevice(string $name, string $otp_secret, string $type = OtpDevice::TYPE_GOOGLE): OtpDevice
    {
        if (Str::length($otp_secret) === 44)
        {
            $otp_secret = Str::substr($otp_secret, 0, 12);
        }

        $device = new OtpDevice(
            compact('name', 'type', 'otp_secret')
        );
        $this->otpDevices()->save($device);

        return $device;
    }

    public function generateGoogleQRCode(string $name, string $email): array
    {
        $secret = Laravel2FA::generateSecretKey();
        $qrcode = (new GenerateGoogleQRCode($secret))->generate("${name}:${email}");

        return compact('secret', 'qrcode');
    }

    public function generateBackupCodes(int $total = 8, $force = false): array
    {
        if ($this->otpBackupCodes()->exists() && !$force)
        {
            return [];
        }

        $this->otpBackupCodes()->delete();

        $codes = (new GenerateBackupCodes())->generate($total);

        Collection::make($codes)
            ->each(fn ($code) => $this->otpBackupCodes()->save(
                new OtpBackupCode([
                    'code' => $code
                ])
            ));

        return $codes;
    }

    public function generateOtpRememberToken(): string
    {
        $token = md5(Str::random());

        $this->otpRememberTokens()
            ->save(new OtpRememberToken([
                'token' => $token
            ]));

        return $token;
    }

    public function has2FAEnabled(): bool
    {
        return config('laravel2fa.enabled') && $this->otpDevices()->exists();
    }
}