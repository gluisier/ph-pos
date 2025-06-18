<?php

namespace App\Tests\Application\Connected;

use App\Entity\User;
use App\Tests\AbstractDatabaseTestCase;

class ConnectionTest extends AbstractDatabaseTestCase
{
    public function setUp(): void
    {
        parent::setup();
        parent::loadFixtures();
    }

    public function testUnregisteredLoginFail(): void
    {
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneByName('unregistered');
        $this->assertNull($user, 'Test unregistered user was registered');

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form([
            '_username' => 'unregistered',
            '_password' => 'unregistered',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/login', 302, 'Unregistered user could log in');
    }

    public function testInactiveLoginFail(): void
    {
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneByName('inactive');
        $this->assertNotNull($user, 'Inactive test user not present in database');
        $this->assertEquals(false, $user->isEnabled());

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form([
            '_username' => 'inactive',
            '_password' => 'inactive',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/login', 302, 'Inactive user could log in');
    }

    public function testLoginSuccessful(): void
    {
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneByName('admin');
        $this->assertNotNull($user, 'Admin test user not present in database');
        $this->assertEquals(true, $user->isEnabled());

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form([
            '_username' => 'admin',
            '_password' => 'admin',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/');
    }
}