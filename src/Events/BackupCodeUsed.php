<?php

namespace MakelarisJR\Laravel2FA\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackupCodeUsed
{
    use Dispatchable, SerializesModels;

    private $authenticatable;
    private $code;

    /**
     * @return void
     */
    public function __construct(Authenticatable $authenticatable, string $code)
    {
        $this->authenticatable = $authenticatable;
        $this->code = $code;
    }
}