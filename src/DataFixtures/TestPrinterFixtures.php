<?php

namespace App\DataFixtures;

use App\Config\Printer\API;
use App\Config\Printer\Status;
use App\Entity\Printer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestPrinterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $printer1 = (new Printer())
            ->setId('test_printer_1')
            ->setModel('test_printer')
            ->setIp('::1')
            ->setPort('8008')
            ->setStatus(Status::OK)
            ->setAPI(API::ESC_POS);
        $manager->persist($printer1);

        $manager->flush();
    }
}
