<?php

namespace MakelarisJR\Laravel2FA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OtpDevice extends Model
{
    const TYPE_GOOGLE  = 'google';
    const TYPE_YUBIKEY = 'yubikey';

    protected $hidden = ['otp_secret'];

    protected $fillable = [
        'name', 'type', 'otp_secret'
    ];

    public function models(): MorphTo
    {
        return $this->morphTo();
    }
}