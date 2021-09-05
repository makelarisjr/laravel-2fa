<?php

namespace MakelarisJR\Laravel2FA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property string $code
 * @property string $model_type
 * @property int    $model_id
 * @property Carbon $used_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Model $models
 */
class OtpBackupCode extends Model
{
    protected $fillable = ['code', 'used_at'];

    public function models(): MorphTo
    {
        return $this->morphTo();
    }
}