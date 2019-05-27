<?php

namespace App\Events;

use App\Models\Brushline;
use App\Models\DungeonRoute;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BrushlineDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var DungeonRoute $_dungeonroute */
    private $_dungeonroute;

    /** @var int $_id */
    private $_id;

    /**
     * Create a new event instance.
     *
     * @param $dungeonroute DungeonRoute
     * @param $brushline Brushline
     * @return void
     */
    public function __construct(DungeonRoute $dungeonroute, Brushline $brushline)
    {
        $this->_dungeonroute = $dungeonroute;
        $this->_id = $brushline->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(sprintf('route-edit.%s', $this->_dungeonroute->public_key));
    }

    public function broadcastAs()
    {
        return 'brushline-deleted';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->_id
        ];
    }
}
