<?php

namespace MakelarisJR\Laravel2FA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property string $token
 * @property string $model_type
 * @property int    $model_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Model $model
 */
class OtpRememberToken extends Model
{
    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected $fillable = [
        'token'
    ];

    public function model(): MorphTo
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