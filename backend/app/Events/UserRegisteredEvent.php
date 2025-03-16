<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class UserRegisteredEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userCount;

    public function __construct($userCount)
    {
        $this->userCount = $userCount;
    }

    public function broadcastOn()
    {
        return new Channel('user-registered'); // Channel name
    }

    public function broadcastAs()
    {
        return 'UserRegisteredEvent'; // Event name
    }
}
