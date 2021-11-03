<?php

namespace MakelarisJR\Laravel2FA\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthorizationFailed
{
    use Dispatchable, SerializesModels;

    public Authenticatable $authenticatable;

    /**
     * @return void
     */
    public function __construct(Authenticatable $authenticatable)
    {
        $this->authenticatable = $authenticatable;
    }
}
