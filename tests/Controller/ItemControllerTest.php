<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\User;
use App\Tests\AbstractDatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ItemControllerTest extends AbstractDatabaseTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $itemRepository;
    private EntityRepository $categoryRepository;
    private string $path = '/item/';

    protected function setUp(): void
    {
        parent::setUp();
        parent::loadFixtures();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->itemRepository = $this->manager->getRepository(Item::class);
        $this->categoryRepository = $this->manager->getRepository(Category::class);
        $user = $this->manager->getRepository(User::class)->findOneByName('admin');
        $this->client->loginUser($user);

        foreach ($this->itemRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Articles | ᴾᴴPOS');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));
        $category = $this->categoryRepository->findAll()[0];

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('✔️', [
            'item[title]' => 'Testing title',
            'item[category]' => $category->getId(),
            'item[label]' => '🧑‍🔬',
            'item[colour]' => '#000000',
            'item[price]' => 1,
            'item[public]' => true,
            'item[ticket]' => true,
            'item[available]' => true,
            'item[separatelySellable]' => true,
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->itemRepository->count([]));
    }

    public function testShow(): void
    {
        $category = $this->categoryRepository->findAll()[0];
        $fixture = (new Item())
            ->setTitle('Show title')
            ->setLabel('🧑‍🔬')
            ->setColour('#000000')
            ->setPrice(1)
            ->setPublic(true)
            ->setPosition(1)
            ->setTicket(true)
            ->setAvailable(true)
            ->setSeparatelySellable(true)
            ->setCategory($category)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();
        self::assertSame(1, $this->itemRepository->count([]));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Show title | ᴾᴴPOS');
    }

    public function testEdit(): void
    {
        $categories = $this->categoryRepository->findBy([], limit: 2);
        $aCategory = $categories[0];
        $anotherCategory = $categories[1];
        $fixture = (new Item())
            ->setTitle('Some title')
            ->setLabel('🧑‍🔬')
            ->setColour('#000000')
            ->setPrice(1)
            ->setPublic(true)
            ->setPosition(1)
            ->setTicket(true)
            ->setAvailable(true)
            ->setSeparatelySellable(true)
            ->setCategory($aCategory)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();
        self::assertSame(1, $this->itemRepository->count([]));

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('✔️', [
            'item[title]' => 'Updated title',
            'item[label]' => '🧑‍🔬',
            'item[colour]' => '#ffffff',
            'item[price]' => 2,
            'item[public]' => false,
            'item[ticket]' => false,
            'item[available]' => false,
            'item[separatelySellable]' => false,
            'item[category]' => $anotherCategory->getId(),
        ]);

        self::assertResponseRedirects('/item/');

        $fixture = $this->itemRepository->findAll()[0];

        self::assertSame('Updated title', $fixture->getTitle());
        self::assertSame('🧑‍🔬', $fixture->getLabel());
        self::assertSame('#ffffff', $fixture->getColour());
        self::assertSame(2, $fixture->getPrice());
        self::assertSame(false, $fixture->isPublic());
        self::assertSame(false, $fixture->hasTicket());
        self::assertSame(false, $fixture->isAvailable());
        self::assertSame(false, $fixture->isSeparatelySellable());
        self::assertSame($anotherCategory->getId(), $fixture->getCategory()->getId());
    }

    public function testRemove(): void
    {
        $fixture = (new Item())
            ->setTitle('Some deleted title')
            ->setLabel('🧑‍🔬💥')
            ->setColour('#888888')
            ->setPrice(1)
            ->setPublic(true)
            ->setPosition(0)
            ->setTicket(true)
            ->setAvailable(true)
            ->setSeparatelySellable(true)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();
        self::assertSame(1, $this->itemRepository->count([]));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('🗑️');

        self::assertResponseRedirects('/item/');
        self::assertSame(0, $this->itemRepository->count([]));
    }

    public function testEventDisplay(): void
    {
        $fixture = (new Item())
            ->setTitle('Event display title')
            ->setLabel('🧪')
            ->setColour('#888888')
            ->setPrice(1)
            ->setStock(1)
            ->setPublic(true)
            ->setPosition(0)
            ->setTicket(true)
            ->setAvailable(true)
            ->setSeparatelySellable(true)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();
        self::assertSame(1, $this->itemRepository->count([]));

        $this->client->request('GET', sprintf('%s%s', $this->path, 'event'));
        $this->assertSelectorTextContains('tbody tr:nth-of-type(1) td:nth-of-type(2)', '🧪');
        $this->assertSelectorTextContains('tbody tr:nth-of-type(1) td:nth-of-type(3)', 'Event display title');
    }

    public function testEventEdit(): void
    {
        $fixture = (new Item())
            ->setTitle('Event item update title')
            ->setLabel('🧪')
            ->setColour('#888888')
            ->setPrice(1)
            ->setStock(1)
            ->setPublic(true)
            ->setPosition(0)
            ->setTicket(true)
            ->setAvailable(true)
            ->setSeparatelySellable(true)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();
        self::assertSame(1, $this->itemRepository->count([]));

        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, 'event'));
        self::assertResponseIsSuccessful('Could not generate event page', verbose: false);

        $form = $crawler->filter('button[type="submit"]')->form();
        $index = array_keys($form->getPhpValues()['event']['items'])[0];
        $values = array_replace_recursive(
            $form->getPhpValues(),
            [
                'event' => [
                    'items' => [
                        $index => [
                            'price' => 2,
                            'stock' => 2,
                        ],
                    ],
                ]
            ]
        );
        unset($values['event']['items'][$index]['available']);

        $crawler = $this->client->request(
            $form->getMethod(),
            $form->getUri(),
            $values
        );
        self::assertResponseRedirects(sprintf('%s', $this->path), verbose: false);

        $fixture = $this->itemRepository->findAll();

        self::assertSame('Event item update title', $fixture[0]->getTitle(), 'Title was updated');
        self::assertSame('🧪', $fixture[0]->getLabel(), 'Label was updated');
        self::assertSame('#888888', $fixture[0]->getColour(), 'Colour was updated');
        self::assertSame(2, $fixture[0]->getPrice(), 'Price was not updated');
        self::assertSame(2, $fixture[0]->getStock(), 'Stock was not updated');
        self::assertSame(true, $fixture[0]->isPublic(), 'Public state was updated');
        self::assertSame(true, $fixture[0]->hasTicket(), 'Ticket state was updated');
        self::assertSame(false, $fixture[0]->isAvailable(), 'Available state was not updated');
        self::assertSame(true, $fixture[0]->isSeparatelySellable(), 'Separately sellable state was updated');
    }

    public function testMenu(): void
    {
        $aCategory = $this->categoryRepository->findOneBy([]);
        $fixture = (new Item())
            ->setTitle('Menu display title')
            ->setLabel('🧪')
            ->setColour('#888888')
            ->setPrice(1)
            ->setPublic(true)
            ->setPosition(0)
            ->setTicket(true)
            ->setAvailable(true)
            ->setSeparatelySellable(true)
            ->setCategory($aCategory)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();
        self::assertSame(1, $this->itemRepository->count([]));

        $crawler = $this->client->request('GET', 'prices');
        $this->assertSelectorTextContains('tbody:nth-of-type(1) tr:nth-of-type(1) td:nth-of-type(1)', 'Menu display title');
        $this->assertSelectorTextContains('tbody:nth-of-type(1) tr:nth-of-type(1) td:nth-of-type(2)', '1,00');
    }
}
