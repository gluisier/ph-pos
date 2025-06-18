<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Tests\AbstractDatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class CategoryControllerTest extends AbstractDatabaseTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $categoryRepository;
    private string $path = '/category/';

    protected function setUp(): void
    {
        parent::setUp();
        parent::loadFixtures();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->categoryRepository = $this->manager->getRepository(Category::class);
        $user = $this->manager->getRepository(User::class)->findOneByName('admin');
        $this->client->loginUser($user);

        foreach ($this->categoryRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Catégories d’articles | ᴾᴴPOS');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('✔️', [
            'category[title]' => 'Testing title',
            'category[label]' => '🧑‍🔬',
            'category[colour]' => '#000000',
            'category[public]' => true,
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->categoryRepository->count([]));
    }

    public function testEdit(): void
    {
        $fixture = (new Category())
            ->setTitle('Some title')
            ->setLabel('🧑‍🔬')
            ->setColour('#000000')
            ->setPublic(true)
            ->setPosition(1)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('✔️', [
            'category[title]' => 'Updated title',
            'category[label]' => '🧑‍🔬',
            'category[colour]' => '#ffffff',
            'category[public]' => false,
        ]);

        self::assertResponseRedirects('/category/');

        $fixture = $this->categoryRepository->findAll();

        self::assertSame('Updated title', $fixture[0]->getTitle());
        self::assertSame('🧑‍🔬', $fixture[0]->getLabel());
        self::assertSame('#ffffff', $fixture[0]->getColour());
        self::assertSame(false, $fixture[0]->isPublic());
    }

    public function testRemove(): void
    {
        $fixture = (new Category())
            ->setTitle('Some deleted title')
            ->setLabel('🧑‍🔬💥')
            ->setColour('#88888')
            ->setPublic(true)
            ->setPosition(0)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s', $this->path));
        $this->client->submitForm('🗑️');

        self::assertResponseRedirects('/category/');
        self::assertSame(0, $this->categoryRepository->count([]));
    }
}
