<?php

namespace App\Tests\Application\Connected;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class MenuLinksCommonTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->findOneByName('user');

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
        $this->assertResponseStatusCodeSame(403, 'Access to category page while connected does not respond with a 403', false);
    }

    public function testItemNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/item/');
        $this->assertResponseStatusCodeSame(403, 'Access to item page while connected does not respond with a 403', false);
    }

    public function testPaymentMethodNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/payment-method/');
        $this->assertResponseStatusCodeSame(403, 'Access to payment method page while connected does not respond with a 403', false);
    }

    public function testOrderNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/order/');
        $this->assertResponseStatusCodeSame(403, 'Access to order page while connected does not respond with a 403', false);
    }

    public function testUserNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/user/');
        $this->assertResponseStatusCodeSame(403, 'Access to user page while connected does not respond with a 403', false);
    }

    public function testPrinterNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/printer/');
        $this->assertResponseStatusCodeSame(403, 'Access to printer page while connected does not respond with a 403', false);
    }

    public function testLocationNavigationLinks(): void
    {
        $crawler = $this->client->request('GET', '/location/');
        $this->assertResponseStatusCodeSame(403, 'Access to location page while connected does not respond with a 403', false);
    }

    private function assertLinks(Crawler $crawler): void
    {
        $this->assertResponseIsSuccessful();

        $navLinks = $crawler->filter('nav #Menu a');
        $linksNumber = 5;
        $this->assertCount($linksNumber, $navLinks, sprintf('Navigation menu does not have %d links', $linksNumber));

        $navHrefs = $navLinks->each(fn ($node) => $node->attr('href'));
        $this->assertContains('/prices', $navHrefs, 'Link to `/prices` is missing in common users navigation menu');
        $this->assertContains('/sales', $navHrefs, 'Link to `/sales` is missing in common users navigation menu');
        $this->assertContains('/production', $navHrefs, 'Link to `/production` is missing in common users navigation menu');
        $this->assertContains('/logout', $navHrefs, 'Link to `/logout` is missing in common users navigation menu');
        $this->assertNotContains('/category/', $navHrefs, 'Link to `/category/` is available to common users in navigation menu');
        $this->assertNotContains('/item/', $navHrefs, 'Link to `/item/` is available to common users in navigation menu');
        $this->assertNotContains('/payment-method/', $navHrefs, 'Link to `/payment-method/` is available to common users in navigation menu');
        $this->assertNotContains('/order/', $navHrefs, 'Link to `/order/` is available to common users in navigation menu');
        $this->assertNotContains('/user/', $navHrefs, 'Link to `/user/` is available to common users in navigation menu');
        $this->assertNotContains('/printer/', $navHrefs, 'Link to `/printer/` is available to common users in navigation menu');
        $this->assertNotContains('/location/', $navHrefs, 'Link to `/location/` is available to common users in navigation menu');
    }
}
