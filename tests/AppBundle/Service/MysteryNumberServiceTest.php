<?php

namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use AppBundle\Service\MysteryNumberService;

class MysteryNumberServiceTest extends WebTestCase
{
	private $mysteryNumberService;

	protected function setUp()
    {
	    $kernel = static::createKernel();
	    $kernel->boot();
	    $this->mysteryNumberService = $kernel->getContainer()->get('mystery-number');
    }

    /**
     * Test min and max mystery number values.
     */
    public function testMinAndMax()
    {
    	// Test type.
    	$this->assertInternalType('int', $this->mysteryNumberService::MAX_NUMBER);
    	$this->assertInternalType('int', $this->mysteryNumberService::MIN_NUMBER);

    	// Test numbers interval.
    	$this->assertTrue($this->mysteryNumberService::MAX_NUMBER - $this->mysteryNumberService::MIN_NUMBER > 1);
    }

	/**
	 * Test play function returns with good input.
	 */
    public function testPlayNormalBehaviour()
    {
        $this->mysteryNumberService->setMysteryNumber($this->mysteryNumberService::MIN_NUMBER + 1);

        $result = $this->mysteryNumberService->play($this->mysteryNumberService->getMysteryNumber() + 1);
        $this->assertEquals($this->mysteryNumberService::RETURNS['Inferior'], $result);

        $result = $this->mysteryNumberService->play($this->mysteryNumberService->getMysteryNumber() - 1);
        $this->assertEquals($this->mysteryNumberService::RETURNS['Superior'], $result);

        $result = $this->mysteryNumberService->play($this->mysteryNumberService->getMysteryNumber());
        $this->assertEquals($this->mysteryNumberService::RETURNS['Equal'], $result);
    }

    /**
     * Test end and new game.
     */
    public function testEndNewGame()
    {
    	$this->mysteryNumberService->setMysteryNumber($this->mysteryNumberService::MIN_NUMBER);

    	$this->mysteryNumberService->play($this->mysteryNumberService->getMysteryNumber());

    	// Test that mystery number is set to null when the number is guessed.
    	$this->assertTrue(is_null($this->mysteryNumberService->getMysteryNumber()));

    	// Test that a new call to play generate a new number.
    	$this->mysteryNumberService->play($this->mysteryNumberService::MIN_NUMBER);
    	$this->assertFalse(is_null($this->mysteryNumberService->getMysteryNumber()));
    }

    /**
     * Test that setMysteryNumber doesn't accept non integer input.
     */
    public function testMysteryNumberSetterException()
    {
    	$this->expectException(\Exception::class);
    	$this->mysteryNumberService->setMysteryNumber('not integer');
    }

    /**
     * Test that play doesn't accept non integer input.
     */
    public function testPlayException()
    {
    	$this->expectException(\Exception::class);
    	$this->mysteryNumberService->play('not integer');
    }
}
