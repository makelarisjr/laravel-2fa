<?php

namespace MakelarisJR\Laravel2FA\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAuthorized
{
    use Dispatchable, SerializesModels;

    public Authenticatable $authenticatable;
    public bool $remember;

    /**
     * @return void
     */
    public function __construct(Authenticatable $authenticatable, bool $remember)
    {
        $this->authenticatable = $authenticatable;
        $this->remember = $remember;
    }
}
