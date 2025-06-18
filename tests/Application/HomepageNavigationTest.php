<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class HomepageNavigationTest extends WebTestCase
{
    private ?Crawler $crawler = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $this->crawler = $client->request('GET', '/');
    }

    public function testHomepageIsSuccessful(): void
    {
        $this->assertResponseIsSuccessful();
    }

    public function testHomepageNavigationBodyLinks(): void
    {
        $containerLinks = $this->crawler->filter('div.container:nth-child(2) a');
        $this->assertCount(3, $containerLinks, 'Main container does not have 3 links');

        $containerHrefs = $containerLinks->each(fn ($node) => $node->attr('href'));
        $this->assertContains('/prices', $containerHrefs, 'Link to `/prices` is missing in main container');
        $this->assertContains('/sales', $containerHrefs, 'Link to `/sales` is missing in main container');
        $this->assertContains('/production', $containerHrefs, 'Link to `/production` is missing in main container');
    }
}
