<?php

namespace App\Tests\Application\NotConnected;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NavigationNotConnectedTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testProductionNavigationIsRedirect(): void
    {
        $this->client->request('GET', '/production');
        $this->assertResponseRedirects('/login', 302, 'Access to production page while not connected does not redirect to login page');
    }

    public function testItemNavigationIsRedirect(): void
    {
        $this->client->request('GET', '/item/');
        $this->assertResponseRedirects('/login', 302, 'Access to items list page while not connected does not redirect to login page');
    }

    public function testCategoryNavigationIsRedirect(): void
    {
        $this->client->request('GET', '/category/');
        $this->assertResponseRedirects('/login', 302, 'Access to categories list page while not connected does not redirect to login page');
    }

    public function testPaymentMethodNavigationIsRedirect(): void
    {
        $this->client->request('GET', '/payment-method/');
        $this->assertResponseRedirects('/login', 302, 'Access to payment methods list page while not connected does not redirect to login page');
    }

    public function testOrderNavigationIsRedirect(): void
    {
        $this->client->request('GET', '/order/');
        $this->assertResponseRedirects('/login', 302, 'Access to orders list page while not connected does not redirect to login page');
    }

    public function testUserNavigationIsRedirect(): void
    {
        $this->client->request('GET', '/user/');
        $this->assertResponseRedirects('/login', 302, 'Access to users list page while not connected does not redirect to login page');
    }
}
