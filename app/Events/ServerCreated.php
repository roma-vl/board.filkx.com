<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServerCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    private mixed $message;

    /**
     * Create a new event instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->message = $data['message'];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        // Канал "private.test.name"
        return new PrivateChannel('test.name');
    }

    /**
     * Визначаємо, як буде називатися подія на фронті
     */
    public function broadcastAs(): string
    {
        return 'server.created';
    }
}
