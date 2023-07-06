<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message, $model,$user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $model, $user)
    {
        $this->message = $message;
        $this->model = $model;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
