<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Allow to get user provided value when checking against mystery number.
 */
class PlayEvent extends Event
{
    protected $guess;

    public function __construct($guess)
    {
        $this->guess = $guess;
    }

    public function getGuess()
    {
        return $this->guess;
    }
}