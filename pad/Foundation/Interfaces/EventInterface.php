<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Interfaces;

interface EventInterface
{
    public function registerObserver();

    public function fire();

}