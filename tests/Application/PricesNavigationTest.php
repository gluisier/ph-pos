<?php

namespace App\Tests\Application;

use App\Tests\AbstractDatabaseTestCase;
use Symfony\Component\DomCrawler\Crawler;

class PricesNavigationTest extends AbstractDatabaseTestCase
{
    private ?Crawler $crawler = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crawler = $this->client->request('GET', '/prices');
    }

    public function testPricesIsSuccessful(): void
    {
        $this->assertResponseIsSuccessful();
    }

    public function testPricesNoLinks(): void
    {
        $bodyLinks = $this->crawler->filter('div.container:nth-child(2) a');
        $this->assertCount(0, $bodyLinks, 'Prices page contains links, it shouldn\'t');
    }
}
