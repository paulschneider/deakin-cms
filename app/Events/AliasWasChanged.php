<?php

namespace App\Events;

use App\Events\Event;

class AliasWasChanged extends Event
{
    //use SerializesModels;

    public $entity;
    public $from;
    public $to;

    /**
     * Create a new event instance.
     *
     * @param  Alias  $alias
     * @return void
     */
    public function __construct($entity, $from, $to)
    {
        $this->entity = $entity;
        $this->from   = $from;
        $this->to     = $to;
    }
}
