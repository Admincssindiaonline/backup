<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\Agreement;
use App\Http\Resources\AgreementResource;

class AgreementEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $channelName;
    public $event;
    public $agreement;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Agreement  $agreement
     * @param string  $name
     *
     * @return void
     */
    public function __construct(Agreement $agreement, string $name)
    {
        $this->channelName = 'user.' . $agreement->owner_id;
        $this->event = $name;
        $this->agreement = (new AgreementResource($agreement))->resolve();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->channelName);
    }
}
