<?php

namespace JD\PhpProjectAnalyzerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test controller
 */
class MainControllerTest extends WebTestCase
{
    /**
     * Test todo
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ppa');

        $this->assertTrue($crawler->filter('html:contains("Project Analyzer :")')->count() > 0);
    }
}
