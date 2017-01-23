<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Socket\Events;

use Pad\Foundation\Interfaces\EventInterface;
use Pad\Foundation\Interfaces\ObserverInterface;
use Pad\Foundation\Socket\Observers\Deliver;

class Insert implements EventInterface
{
    protected $observers = [];

    protected $data;


    public function __construct($data)
    {
        $this->data = $data;
    }

    public function registerObserver(ObserverInterface $observer)
    {
        $this->observers[] = $observer;
    }

    public function fire()
    {
        foreach ($this->observers as $key => $observer) {
            $observer->handle($this->data);
        }
    }
}