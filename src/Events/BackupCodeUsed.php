<?php

namespace MakelarisJR\Laravel2FA\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackupCodeUsed
{
    use Dispatchable, SerializesModels;

    public Authenticatable $authenticatable;
    public string $code;

    /**
     * @return void
     */
    public function __construct(Authenticatable $authenticatable, string $code)
    {
        $this->authenticatable = $authenticatable;
        $this->code = $code;
    }
}
