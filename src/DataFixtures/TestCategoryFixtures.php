<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category1 = (new Category())
            ->setTitle('Vaisselle réutilisable')
            ->setLabel('🪙')
            ->setColour('#dcdcdc')
            ->setPublic(true)
            ->setPosition(0);
        $manager->persist($category1);
        $this->addReference('category1', $category1);
        $category2 = (new Category())
            ->setTitle('Non alcoolisé')
            ->setLabel('💦')
            ->setColour('#0000aa')
            ->setPublic(true)
            ->setPosition(1);
        $manager->persist($category2);
        $this->addReference('category2', $category2);
        $category3 = (new Category())
            ->setTitle('Bière')
            ->setLabel('🍻')
            ->setColour('#f4a70c')
            ->setPublic(true)
            ->setPosition(2);
        $manager->persist($category3);
        $this->addReference('category3', $category3);
        $category4 = (new Category())
            ->setTitle('Vin')
            ->setLabel('🍇')
            ->setColour('#800080')
            ->setPublic(true)
            ->setPosition(3);
        $manager->persist($category4);
        $this->addReference('category4', $category4);
        $category5 = (new Category())
            ->setTitle('Nourriture')
            ->setLabel('🍴')
            ->setColour('#f0a37a')
            ->setPublic(true)
            ->setPosition(4);
        $manager->persist($category5);
        $this->addReference('category5', $category5);
        $category6 = (new Category())
            ->setTitle('Pack')
            ->setLabel('📦')
            ->setColour('#ffd8bf')
            ->setPublic(false)
            ->setPosition(5);
        $manager->persist($category6);
        $this->addReference('category6', $category6);
        $category7 = (new Category())
            ->setTitle('Couleur de vin')
            ->setLabel( '⚪')
            ->setPublic(false)
            ->setPosition(6);
        $manager->persist($category7);
        $this->addReference('category7', $category7);
        $category8 = (new Category())
            ->setTitle('Volume')
            ->setLabel( '🍶')
            ->setPublic(false)
            ->setPosition(7);
        $manager->persist($category8);
        $this->addReference('category8', $category8);
        $category9 = (new Category())
            ->setTitle('Viande')
            ->setLabel( '🔪')
            ->setPublic(false)
            ->setPosition(8);
        $manager->persist($category9);
        $this->addReference('category9', $category9);
        $category10 = (new Category())
            ->setTitle( 'Accompagnement')
            ->setLabel( '🥗')
            ->setPublic(false)
            ->setPosition(9);
        $manager->persist($category10);
        $this->addReference('category10', $category10);

        $manager->flush();
    }
}
