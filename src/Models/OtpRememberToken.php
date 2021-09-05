<?php

namespace MakelarisJR\Laravel2FA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OtpRememberToken extends Model
{
    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected $fillable = [
        'token'
    ];

    public function models(): MorphTo
    {
        return $this->morphTo();
    }

    public function hasExpired(): bool
    {
        return $this->created_at
            ->addMinutes(config('laravel2fa.remember_cookie.lifetime'))
            ->isPast();
    }
}