<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class LoginNavigationTest extends WebTestCase
{
    private ?Crawler $crawler = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $this->crawler = $client->request('GET', '/login');
    }

    public function testLoginIsSuccessful(): void
    {
        $this->assertResponseIsSuccessful();
    }

    public function testLoginFormFields(): void
    {
        $formInputs = $this->crawler->filter('form input');
        $this->assertCount(3, $formInputs, 'Login form does not contain 3 inputs');

        $inputNames = $formInputs->each(fn ($node) => $node->attr('name'));
        $this->assertContains('_username', $inputNames, 'Field for username is not present in login form');
        $this->assertContains('_password', $inputNames, 'Field for password is not present in login form');
        $this->assertContains('_csrf_token', $inputNames, 'Login form is not CSRF-protected');
    }

    public function testLoginFormButton(): void
    {
        $formButton = $this->crawler->filter('form button');
        $this->assertCount(1, $formButton, 'Login form does not have any button');

        $formButtonTypes = $formButton->each(fn ($node) => $node->attr('type'));
        $this->assertContains('submit', $formButtonTypes, 'No submit button for login form');
    }

    public function testLoginRegisterLink(): void
    {
        $links = $this->crawler->filter('div.container:nth-child(2) a');
        $this->assertGreaterThanOrEqual(1, count($links), 'Login page does not provide any link');

        $containerHrefs = $links->each(fn ($node) => $node->attr('href'));
        $this->assertContains('/register', $containerHrefs, 'Login page does not provide registration link');
    }
}
