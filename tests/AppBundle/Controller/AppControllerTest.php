<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
	/**
	 * Test index route.
	 */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Loading', $crawler->filter('#vueApp')->text());
    }

    /**
     * Test that not existing route returns 404.
     */
    public function testNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/this-route-doesnt-exists-and-will-probably-never-exists-AZ');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
