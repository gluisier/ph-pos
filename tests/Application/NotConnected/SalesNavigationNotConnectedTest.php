<?php

namespace App\Tests\Application\NotConnected;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SalesNavigationNotConnectedTest extends WebTestCase
{
    private ?Crawler $crawler = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $this->crawler = $client->request('GET', '/sales');
    }

    public function testPricesIsSuccessful(): void
    {
        $this->assertResponseIsSuccessful();
    }

    public function testPricesNoSubmitButtons(): void
    {
        $bodySubmits = $this->crawler->filter('div.container:nth-child(2) button');
        $this->assertCount(0, $bodySubmits, 'Sales page contains submit buttons while not connected, it shouldn\'t');
    }
}
