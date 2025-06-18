<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\PaymentMethod;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestPaymentMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $paymentMethod1 = (new PaymentMethod())
            ->setId('cash')
            ->setTitle('Comptant')
            ->setLabel('👛')
            ->setColour('#d4af37')
            ->setPublic(true)
            ->setAvailable(true)
            ->setPosition(0);
        $manager->persist($paymentMethod1);
        $paymentMethod2 = (new PaymentMethod())
            ->setId('township')
            ->setTitle('Commune')
            ->setLabel('🐏')
            ->setColour('#0080ff')
            ->setPublic(0)
            ->setAvailable(true)
            ->setPosition(2);
        $manager->persist($paymentMethod2);
        $paymentMethod3 = (new PaymentMethod())
            ->setId('twint')
            ->setTitle( 'Twint')
            ->setLabel('📱')
            ->setColour('#000000')
            ->setPublic(true)
            ->setAvailable(true)
            ->setPosition(1);
        $manager->persist($paymentMethod3);

        $manager->flush();
    }
}
