<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class RegisterNavigationTest extends WebTestCase
{
    private ?Crawler $crawler = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $this->crawler = $client->request('GET', '/register');
    }

    public function testRegisterIsSuccessful(): void
    {
        $this->assertResponseIsSuccessful();
    }

    public function testRegisterFormFields(): void
    {
        $formInputs = $this->crawler->filter('form input');
        $this->assertCount(3, $formInputs, 'Register form does not contain 3 inputs');

        $inputNames = $formInputs->each(fn ($node) => $node->attr('name'));
        $this->assertContains('registration_form[name]', $inputNames, 'Field for username is not present in login form');
        $this->assertContains('registration_form[plainPassword]', $inputNames, 'Field for password is not present in login form');
        $this->assertContains('registration_form[_token]', $inputNames, 'Login form is not CSRF-protected');
    }

    public function testRegisterFormButton(): void
    {
        $formButton = $this->crawler->filter('form button');
        $this->assertCount(1, $formButton, 'Register form does not have any button');

        $formButtonTypes = $formButton->each(fn ($node) => $node->attr('type'));
        $this->assertContains('submit', $formButtonTypes, 'No submit button for login form');
    }
}
