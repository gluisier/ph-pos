<?php

namespace App\Tests\Application\Connected;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NavigationConnectedTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneByName('user');

        $this->client->loginUser($user);
    }

    public function testProductionNavigationIsSuccessful(): void
    {
        $this->client->request('GET', '/production');
        $this->assertResponseIsSuccessful('Unable to access production page while connected');
    }

    public function testItemNavigationisForbidden(): void
    {
        $this->client->request('GET', '/item/');
        $this->assertResponseStatusCodeSame(403, 'Access to items list page while connected does not redirect to login page', false);
    }

    public function testCategoryNavigationisForbidden(): void
    {
        $this->client->request('GET', '/category/');
        $this->assertResponseStatusCodeSame(403, 'Access to categories list page while connected does not redirect to login page', false);
    }

    public function testPaymentMethodNavigationisForbidden(): void
    {
        $this->client->request('GET', '/payment-method/');
        $this->assertResponseStatusCodeSame(403, 'Access to payment methods list page while connected does not redirect to login page', false);
    }

    public function testOrderNavigationisForbidden(): void
    {
        $this->client->request('GET', '/order/');
        $this->assertResponseStatusCodeSame(403, 'Access to orders list page while connected does not redirect to login page', false);
    }

    public function testUserNavigationisForbidden(): void
    {
        $this->client->request('GET', '/user/');
        $this->assertResponseStatusCodeSame(403, 'Access to users list page while connected does not redirect to login page', false);
    }
}
