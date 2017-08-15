<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Event\PlayEvent;
use AppBundle\AppEvents;

/**
 * This service hold an integer value and check user input against it.
 * When the value is guessed, a new one is generated.
 * The number is stored in session.
 */
class MysteryNumberService extends Controller
{
    /** @var int Min mystery number value */
    const MIN_NUMBER = 0;
    /** @var int Max mystery number value */
    const MAX_NUMBER = 100;
    /** @var array<string> Values returned for the check against the mystery number */
    const RETURNS = [
        'Inferior' => '-',
        'Superior' => '+',
        'Equal'    => '=',
    ];
    /** @var string Key for mystery number in storage */
    const mysteryNumberStorageKey = 'mysteryNumber';

    /** @var int Mystery number to guess */
    private $mysteryNumber = null;

    /** Constructor */
    public function __construct(SessionInterface $session, EventDispatcherInterface $dispatcher)
    {
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->mysteryNumber = $this->getMysteryNumberFromStorage();
    }

    /**
     * Check provided integer against mysteryNumber.
     * @param int $guess The value we check against mystery number.
     * @return string The position of the mystery number compared to $guess.
     */
    public function play($guess)
    {
        if (!is_integer($guess)) {
            throw new \Exception('Parameter 1 must be an integer.', 1);
        }

        if (is_null($this->mysteryNumber)) {
            $this->setNewMysteryNumber();
        }

        $this->dispatchPlayEvent($guess);

        if ($this->mysteryNumber < $guess) {
            return MysteryNumberService::RETURNS['Inferior'];
        }

        if ($this->mysteryNumber > $guess) {
            return MysteryNumberService::RETURNS['Superior'];
        }

        $this->clearMysteryNumber();
        return MysteryNumberService::RETURNS['Equal'];
    }

    /** MysteryNumber getter. */
    public function getMysteryNumber() { return $this->mysteryNumber; }

    /** MysteryNumber setter. */
    public function setMysteryNumber($number)
    {
        if (!is_integer($number) && !is_null($number)) {
            throw new \Exception('Mystery number must be an integer or null.', 1);
        }

        $this->mysteryNumber = $number;
        $this->persistMysteryNumber($number);
    }

    /**
     * Set mystery number to null.
     */
    public function clearMysteryNumber()
    {
        $this->setMysteryNumber(null);
    }

    /**
     * Regenerate the mystery number.
     */
    public function setNewMysteryNumber()
    {
        $number = $this->generateRandomNumber();
        $this->setMysteryNumber($number);
    }

    /**
     * Dispatch a play event.
     * @param int $guess Player provided value.
     */
    private function dispatchPlayEvent($guess)
    {
        $this->dispatcher->dispatch(AppEvents::onPlay, new PlayEvent($guess));
    }

    /**
     * Generate a random number between MIN_NUMBER and MAX_NUMBER.
     * @return int The generated number.
     */
    private function generateRandomNumber()
    {
        return mt_rand(MysteryNumberService::MIN_NUMBER, MysteryNumberService::MAX_NUMBER);
    }

    /**
     * Store mystery number in session.
     */
    private function persistMysteryNumber()
    {
        if (is_null($this->mysteryNumber)) {
            $this->session->remove(MysteryNumberService::mysteryNumberStorageKey);
        }
        else {
            $this->session->set(MysteryNumberService::mysteryNumberStorageKey, $this->mysteryNumber);
        }
    }

    /**
     * Get mystery number from session.
     * @return int|null Mystery number retreived from session.
     */
    private function getMysteryNumberFromStorage()
    {
        return $this->session->get(MysteryNumberService::mysteryNumberStorageKey, null);
    }
}