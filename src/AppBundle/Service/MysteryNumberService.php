<?php

namespace AppBundle\Service;

/**
 * This service hold an integer value and check user input against it.
 * When the value is guessed, a new one is generated.
 */
class MysteryNumberService
{
    /** @var int Min mystery number value */
    const MIN_NUMBER = 0;
    /** @var int Max mystery number value */
    const MAX_NUMBER = 100;
    /** @var array<string> Values returned for the check against the mystery number */
    const RETURNS = [
        'Inferior' => '<',
        'Superior' => '>',
        'Equal'    => '=',
    ];

    /** @var int Mystery number to guess */
    private $mysteryNumber;

    /**
     * Check provided integer against mysteryNumber.
     * @param int $guess The value we check against mystery number.
     * @return string The position of the mystery number compared to $guess.
     */
    public function play($guess)
    {
        if (!is_integer($guess)) {
            throw new Exception('Error parameter must be an integer.', 1);
        }

        if (empty($mysteryNumber)) {
            $this->mysteryNumber = $this->generateNumber();
        }

        if ($mysteryNumber < $guess) {
            return MysteryNumberService::RETURNS['Inferior'];
        }

        if ($mysteryNumber > $guess) {
            return MysteryNumberService::RETURNS['Superior'];
        }

        $this->mysteryNumber = $this->generateNumber();
        return MysteryNumberService::RETURNS['Equal'];
    }

    /**
     * Generate a random number between MIN_NUMBER and MAX_NUMBER.
     * @return int The generated number.
     */
    private function generateNumber()
    {
        return mt_rand(MysteryNumberService::MIN_NUMBER, MysteryNumberService::MAX_NUMBER);
    }
}