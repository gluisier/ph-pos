<?php

namespace App\Tests\Controller;

use App\Config\Printer\API;
use App\Config\Printer\Status;
use App\Entity\Printer;
use App\Entity\User;
use App\Tests\AbstractDatabaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class PrinterControllerTest extends AbstractDatabaseTestCase
{
    private EntityManagerInterface $manager;
    private EntityRepository $printerRepository;
    private string $path = '/printer/';

    protected function setUp(): void
    {
        parent::setUp();
        parent::loadFixtures();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->printerRepository = $this->manager->getRepository(Printer::class);
        $user = $this->manager->getRepository(User::class)->findOneByName('admin');
        $this->client->loginUser($user);

        foreach ($this->printerRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Imprimantes | ᴾᴴPOS');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('✔️', [
            'printer[id]' => 'test_printer',
            'printer[ip]' => '192.168.192.168',
            'printer[port]' => 8008,
            'printer[api]' => API::EPSON_XML->value,
            'printer[model]' => 'TM-T20III',
            'printer[status]' => Status::STORED->value,
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->printerRepository->count([]));
    }

    public function testShow(): void
    {
        $fixture = (new Printer())
            ->setId('test_printer')
            ->setIp('192.168.192.168')
            ->setPort(8008)
            ->setAPI(API::EPSON_XML)
            ->setModel('TM-T20III')
            ->setStatus(Status::DISABLED)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TM-T20III (Epson xml) | ᴾᴴPOS');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = (new Printer())
            ->setId('test_printer_edit')
            ->setIp('192.168.192.169')
            ->setPort(8009)
            ->setAPI(API::EPSON_XML)
            ->setModel('TM-T20II')
            ->setStatus(Status::ERROR)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('✔️', [
            'printer[id]' => 'test_printer_edited',
            'printer[ip]' => '192.168.192.170',
            'printer[port]' => 8010,
            'printer[api]' => API::EPSON_XML->value,
            'printer[model]' => 'TM-T20IV',
            'printer[status]' => Status::PRINTING->value,
        ]);

        self::assertResponseRedirects('/printer/');

        $fixture = $this->printerRepository->findAll();

        self::assertSame('test_printer_edited', $fixture[0]->getId());
        self::assertSame('192.168.192.170', $fixture[0]->getIp());
        self::assertSame(8010, $fixture[0]->getPort());
        self::assertSame(API::EPSON_XML, $fixture[0]->getAPI());
        self::assertSame('TM-T20IV', $fixture[0]->getModel());
        self::assertSame(Status::PRINTING, $fixture[0]->getStatus());
    }

    public function testRemove(): void
    {
        $fixture = (new Printer())
            ->setId('test_printer_delete')
            ->setIp('192.168.192.171')
            ->setPort(8010)
            ->setAPI(API::ESC_POS)
            ->setModel('equip_351002')
            ->setStatus(Status::ERROR)
        ;

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('🗑️');

        self::assertResponseRedirects('/printer/');
        self::assertSame(0, $this->printerRepository->count([]));
    }
}
