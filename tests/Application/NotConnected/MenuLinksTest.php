<?php

namespace App\Tests\Application\NotConnected;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class MenuLinksTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testNotConnectedHomepageNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertLinks($crawler);
    }

    public function testNotConnectedPricesNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/prices');

        $this->assertLinks($crawler);
    }

    public function testNotConnectedLoginNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertLinks($crawler);
    }

    public function testNotConnectedRegisterNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertLinks($crawler);
    }

    private function assertLinks(Crawler $crawler): void
    {
        $this->assertResponseIsSuccessful();

        $navLinks = $crawler->filter('nav #Menu a');
        $this->assertCount(2, $navLinks, 'Navigation menu does not have 2 links');

        $navHrefs = $navLinks->each(fn ($node) => $node->attr('href'));
        $this->assertContains('/prices', $navHrefs, 'Link to `/prices` is missing in navigation menu');
        $this->assertContains('/login', $navHrefs, 'Link to `/login` is missing in navigation menu');
    }
}
