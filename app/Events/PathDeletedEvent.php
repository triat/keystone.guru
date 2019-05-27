<?php

namespace App\Events;

use App\Models\DungeonRoute;
use App\Models\Path;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PathDeletedEvent implements ShouldBroadcast
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
     * @param $path Path
     * @return void
     */
    public function __construct(DungeonRoute $dungeonroute, Path $path)
    {
        $this->_dungeonroute = $dungeonroute;
        $this->_id = $path->id;
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
        return 'path-deleted';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->_id
        ];
    }
}
