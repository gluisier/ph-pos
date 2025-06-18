<?php

namespace App\Tests\Controller;

use App\Entity\Location;
use App\Entity\User;
use App\Tests\AbstractDatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class LocationControllerTest extends AbstractDatabaseTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $locationRepository;
    private string $path = '/location/';

    protected function setUp(): void
    {
        parent::setUp();
        parent::loadFixtures();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->locationRepository = $this->manager->getRepository(Location::class);
        $user = $this->manager->getRepository(User::class)->findOneByName('admin');
        $this->client->loginUser($user);

        foreach ($this->locationRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Emplacements | ᴾᴴPOS');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('✔️', [
            'location[name]' => 'Testing name',
            'location[description]' => 'Testing description',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->locationRepository->count([]));
    }

    public function testShow(): void
    {
        $fixture = new Location();
        $fixture->setName('Ici');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ici | ᴾᴴPOS');
    }

    public function testEdit(): void
    {
        $fixture = new Location();
        $fixture->setName('Là');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('✔️', [
            'location[name]' => 'Là-bas',
            'location[description]' => 'Une nouvelle description',
        ]);

        self::assertResponseRedirects('/location/');

        $fixture = $this->locationRepository->findAll();

        self::assertSame('Là-bas', $fixture[0]->getName());
        self::assertSame('Une nouvelle description', $fixture[0]->getDescription());
    }

    public function testRemove(): void
    {
        $fixture = new Location();
        $fixture->setName('Delete');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('🗑️');

        self::assertResponseRedirects('/location/');
        self::assertSame(0, $this->locationRepository->count([]));
    }
}
