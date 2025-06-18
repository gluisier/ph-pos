<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\PaymentMethod;
use App\Entity\User;
use App\Tests\AbstractDatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class OrderControllerTest extends AbstractDatabaseTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $orderRepository;
    private string $path = '/order/';

    protected function setUp(): void
    {
        parent::setUp();
        parent::loadFixtures();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->orderRepository = $this->manager->getRepository(Order::class);
        $user = $this->manager->getRepository(User::class)->findOneByName('admin');
        $this->client->loginUser($user);

        foreach ($this->orderRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commandes | ᴾᴴPOS');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $aPaymentMethod = $this->manager->getRepository(PaymentMethod::class)->findAll()[0];
        $crawler = $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $form = $crawler->filter('button[type="submit"]')->form();
        $values = array_replace_recursive(
            $form->getPhpValues(),
            [
                'order' => [
                    'lines' => [
                        0 => [
                            'item' => 1,
                            'quantity' => 1,
                        ],
                    ],
                    'paymentMethod' => $aPaymentMethod->getId(),
                    'externalId' => uniqid(),
                    'printDate' => (new \DateTimeImmutable())->format('Y-m-d\TH:i:s.u\Z')
                ]
            ]
        );

        $crawler = $this->client->request(
            $form->getMethod(),
            $form->getUri(),
            $values
        );

        self::assertResponseRedirects($this->path, verbose: false);

        self::assertSame(1, $this->orderRepository->count([]));
    }

    public function testShow(): void
    {
        $aPaymentMethod = $this->manager->getRepository(PaymentMethod::class)->findAll()[0];
        $externalId = uniqid();
        $fixture = (new Order())
            ->setPaymentMethod($aPaymentMethod)
            ->setExternalId($externalId)
            ->setPrintDate(new \DateTime())
            ->setCreatedAt(new \DateTime())
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commande ' . $externalId . ' | ᴾᴴPOS');
    }

    public function testEdit(): void
    {
        $paymentMethods = $this->manager->getRepository(PaymentMethod::class)->findAll();
        $item = $this->manager->getRepository(\App\Entity\Item::class)->find(1);
        $aPaymentMethod = $paymentMethods[0];
        $anotherPaymentMethod = $paymentMethods[1];
        $fixture = new Order();
        $fixture
            ->addLine(new OrderLine($fixture, $item, 1))
            ->setPaymentMethod($aPaymentMethod)
            ->setExternalId(uniqid())
            ->setPrintDate(new \DateTime())
            ->setCreatedAt(new \DateTime())
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('✔️', [
            'order[paymentMethod]' => $anotherPaymentMethod->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        $fixture = $this->orderRepository->findAll();

        self::assertSame($anotherPaymentMethod->getId(), $fixture[0]->getPaymentMethod()->getId());
    }

    public function testRemove(): void
    {
        $aPaymentMethod = $this->manager->getRepository(PaymentMethod::class)->findAll()[0];
        $fixture = (new Order())
            ->setPaymentMethod($aPaymentMethod)
            ->setExternalId(uniqid())
            ->setPrintDate(new \DateTime())
            ->setCreatedAt(new \DateTime())
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('🗑️');

        self::assertResponseRedirects('/order/');
        self::assertSame(0, $this->orderRepository->count([]));
    }
}
