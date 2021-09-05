<?php

namespace MakelarisJR\Laravel2FA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OtpBackupCode extends Model
{
    protected $fillable = ['code', 'used_at'];

    public function models(): MorphTo
    {
        return $this->morphTo();
    }
}