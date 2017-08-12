<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * This service hold an integer value and check user input against it.
 * When the value is guessed, a new one is generated.
 * The number is stored in session.
 */
class MysteryNumberService
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

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->mysteryNumber = $this->getMysteryNumberFromStorage();
    }

    /**
     * Check provided integer against mysteryNumber.
     * @param int $guess The value we check against mystery number.
     * @return string The position of the mystery number compared to $guess.
     */
    public function play($guess)
    {
        if (is_null($this->mysteryNumber)) {
            $this->setNewMysteryNumber();
        }

        if (!is_integer($guess)) {
            throw new \Exception('Error parameter must be an integer.', 1);
        }

        if ($this->mysteryNumber < $guess) {
            return MysteryNumberService::RETURNS['Inferior'];
        }

        if ($this->mysteryNumber > $guess) {
            return MysteryNumberService::RETURNS['Superior'];
        }

        $this->clearMysteryNumber();
        return MysteryNumberService::RETURNS['Equal'];
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
     * Set mystery number to null.
     */
    private function clearMysteryNumber()
    {
        $this->mysteryNumber = null;
        $this->persistMysteryNumber();
    }

    /**
     * Regenerate the mystery number.
     */
    private function setNewMysteryNumber()
    {
        $number = $this->generateRandomNumber();
        $this->mysteryNumber = $number;
        $this->persistMysteryNumber();
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