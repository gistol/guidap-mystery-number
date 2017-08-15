<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use AppBundle\Service\MysteryNumberService;

class MysteryNumberControllerTest extends WebTestCase
{
	private $route = '/api/mystery';

	private function createValidClient()
	{
		$client = static::createClient();
		$trustedHttpReferers = $client->getKernel()->getContainer()->getParameter('trusted_client_referers');
		$client = static::createClient(array(), array(
		    'HTTP_REFERER' => $trustedHttpReferers[0],
		));

		return $client;
	}

	/**
	 * Test a valid request to API.
	 */
    public function testWorkingPlay()
    {
    	$client = $this->createValidClient();

    	$trustedHttpReferers = $client->getKernel()->getContainer()->getParameter('trusted_client_referers');
        $client->request(
		    'GET',
		    $this->route,
		    [ 'guess' => MysteryNumberService::MIN_NUMBER + 1 ]
		);

        // Test that request succeeded.
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test that JSON is returned.
        $this->assertSame(
            'application/json',
            $client->getResponse()->headers->get('Content-Type')
        );

        // Test that response data has good structure and content.
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $this->assertArrayHasKey('data', $content);
        $this->assertSame($content['success'], true);
        $this->assertContains($content['data'], MysteryNumberService::RETURNS);
    }

    /**
     * Test non valid requests to API.
     */
    public function testBadGuessParameter()
    {
    	// Test absence of required parameter.
    	$client = $this->createValidClient();
    	$client->request(
		    'GET',
		    $this->route
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());


    	// Test out of range parameter.
    	$client = $this->createValidClient();
    	$client->request(
		    'GET',
		    $this->route,
		    [ 'guess' => MysteryNumberService::MIN_NUMBER - 1 ]
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());

		$client = $this->createValidClient();
		$client->request(
		    'GET',
		    $this->route,
		    [ 'guess' => MysteryNumberService::MAX_NUMBER + 1 ]
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());


		// Test non integer parameter.
		$client = $this->createValidClient();
		$client->request(
		    'GET',
		    $this->route,
		    [ 'guess' => 'aa' ]
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());

		$client = $this->createValidClient();
		$client->request(
		    'GET',
		    $this->route,
		    [ 'guess' => 0.1 ]
		);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test that a bad referer is not allowed to call the API.
     */
    public function testRejectedReferer()
    {
    	$client = static::createClient();

    	$client->request(
		    'GET',
		    $this->route,
		    [ 'guess' => MysteryNumberService::MIN_NUMBER + 1 ]
		);
		$this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
