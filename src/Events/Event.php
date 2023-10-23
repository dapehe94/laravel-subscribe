<?php

namespace Dapehe94\LaravelSubscribe\Events;

use Illuminate\Database\Eloquent\Model;

class Event
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|\Dapehe94\LaravelSubscribe\Subscribes
     */
    public $subscribes;

    /**
     * Event constructor.
     */
    public function __construct(Model $subscribes)
    {
        $this->subscribes = $subscribes->refresh();
    }
}
