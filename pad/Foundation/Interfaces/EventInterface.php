<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Interfaces;

interface EventInterface
{
    public function registerObserver(ObserverInterface $observer);

    public function fire();

}