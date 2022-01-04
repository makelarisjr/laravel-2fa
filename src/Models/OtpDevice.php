<?php

namespace MakelarisJR\Laravel2FA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property string $name
 * @property string $type
 * @property string $otp_secret
 * @property string $model_type
 * @property int    $model_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Model $model
 */
class OtpDevice extends Model
{
    const TYPE_GOOGLE = 'google';
    const TYPE_YUBIKEY = 'yubikey';

    protected $hidden = ['otp_secret'];

    protected $fillable = [
        'name', 'type', 'otp_secret',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
