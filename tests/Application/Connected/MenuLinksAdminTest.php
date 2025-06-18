<?php

namespace App\Tests\Application\Connected;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class MenuLinksAdminTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->findOneByName('admin');

        $this->client->loginUser($user);
    }

    public function testHomepageNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertLinks($crawler);
    }

    public function testPricesNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/prices');

        $this->assertLinks($crawler);
    }

    public function testLoginNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertLinks($crawler);
    }

    public function testRegisterNavigationMenuLinks(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertLinks($crawler);
    }

    public function testCategoryNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/category/');

        $this->assertLinks($crawler);
    }

    public function testItemNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/item/');

        $this->assertLinks($crawler);
    }

    public function testPaymentMethodNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/payment-method/');

        $this->assertLinks($crawler);
    }

    public function testOrderNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/order/');

        $this->assertLinks($crawler);
    }

    public function testUserNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/user/');

        $this->assertLinks($crawler);
    }

    public function testPrinterNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/printer/');

        $this->assertLinks($crawler);
    }

    public function testLocationNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/location/');

        $this->assertLinks($crawler);
    }

    private function assertLinks(Crawler $crawler): void
    {
        $this->assertResponseIsSuccessful();

        $navLinks = $crawler->filter('nav #Menu a');
        // Don't forget to count dropdown links
        $linksNumber = 15;
        $this->assertCount($linksNumber, $navLinks, sprintf('Navigation menu does not have %d links', $linksNumber));

        $navHrefs = $navLinks->each(fn ($node) => $node->attr('href'));
        $this->assertContains('/prices', $navHrefs, 'Link to `/prices` is missing in admin navigation menu');
        $this->assertContains('/sales', $navHrefs, 'Link to `/sales` is missing in admin navigation menu');
        $this->assertContains('/production', $navHrefs, 'Link to `/production` is missing in admin navigation menu');
        $this->assertContains('/logout', $navHrefs, 'Link to `/logout` is missing in admin navigation menu');
        $this->assertContains('/category/', $navHrefs, 'Link to `/category/` is missing in admin navigation menu');
        $this->assertContains('/item/', $navHrefs, 'Link to `/item/` is missing in admin navigation menu');
        $this->assertContains('/item/event', $navHrefs, 'Link to `/item/event` is missing in admin navigation menu');
        $this->assertContains('/payment-method/', $navHrefs, 'Link to `/payment-method/` is missing in admin navigation menu');
        $this->assertContains('/order/', $navHrefs, 'Link to `/order/` is missing in admin navigation menu');
        $this->assertContains('/user/', $navHrefs, 'Link to `/user/` is missing in admin navigation menu');
        $this->assertContains('/printer/', $navHrefs, 'Link to `/printer/` is missing in admin navigation menu');
        $this->assertContains('/location/', $navHrefs, 'Link to `/location/` is missing in admin navigation menu');
    }
}
