<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $token
    ) {
    }

    public function broadcastOn(): \Illuminate\Broadcasting\Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
