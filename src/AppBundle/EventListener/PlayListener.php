<?php

namespace AppBundle\EventListener;
use Symfony\Component\Filesystem\Filesystem;

use AppBundle\Event\PlayEvent;

/**
 * Listen to play events and log user input to a file.
 */
class PlayListener
{
    protected $fs;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    /**
     * Log guess attribute to a file.
     * @param PlayEvent $event
     */
    public function onMysteryNumberPlay(PlayEvent $event)
    {
        $guess = $event->getGuess();

        $this->fs->appendToFile('userGuesses.txt', $guess.' ');
    }
}