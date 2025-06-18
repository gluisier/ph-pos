<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = (new Item())
            ->setCategory($this->getReference('category2', Category::class))
            ->setVariantOf(null)
            ->setTitle('Coca')
            ->setLabel('🥤⚫')
            ->setPrice(4)
            ->setColour('#ff0000')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(1);
        $manager->persist($product1);
        $product2 = (new Item())
            ->setCategory($this->getReference('category2', Category::class))
            ->setVariantOf(null)
            ->setTitle('Ice Tea (citron)')
            ->setLabel('🥤🟤')
            ->setPrice(4)
            ->setColour('#ffff00')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(2);
        $manager->persist($product2);
        $product3 = (new Item())
            ->setCategory($this->getReference('category2', Category::class))
            ->setVariantOf(null)
            ->setTitle('Henniez verte')
            ->setLabel('🥤🫧')
            ->setPrice(4)
            ->setColour('#008000')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(3);
        $manager->persist($product3);
        $product4 = (new Item())
            ->setCategory($this->getReference('category2', Category::class))
            ->setVariantOf(null)
            ->setTitle('Henniez bleue')
            ->setLabel('🥤💧')
            ->setPrice(4)
            ->setColour('#0080ff')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(4);
        $manager->persist($product4);
        $product5 = (new Item())
            ->setCategory($this->getReference('category3', Category::class))
            ->setVariantOf(null)
            ->setTitle('Blonde (La Vitamine)')
            ->setLabel('🍺')
            ->setPrice(4)
            ->setColour('#ec9d00')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(5);
        $manager->persist($product5);
        $product6 = (new Item())
            ->setCategory($this->getReference('category3', Category::class))
            ->setVariantOf(null)
            ->setTitle('Blonde + verre')
            ->setLabel('🍺🥛')
            ->setPrice(6)
            ->setColour('#e4bd6e')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(6);
        $manager->persist($product6);
        $product7 = (new Item())
            ->setCategory($this->getReference('category3', Category::class))
            ->setVariantOf(null)
            ->setTitle('IPA (La Minette)')
            ->setLabel('✨🍺')
            ->setPrice(5)
            ->setColour('#f6c101')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(false)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(7);
        $manager->persist($product7);
        $product8 = (new Item())
            ->setCategory($this->getReference('category3', Category::class))
            ->setVariantOf(null)
            ->setTitle('IPA + verre')
            ->setLabel('✨🍺🥛')
            ->setPrice(7)
            ->setColour('#e9cf6f')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(false)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(8);
        $manager->persist($product8);
        $product9 = (new Item())
            ->setCategory($this->getReference('category7', Category::class))
            ->setVariantOf(null)
            ->setTitle('Blanc')
            ->setLabel('🟡')
            ->setPrice(null)
            ->setColour('#fffacd')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(9);
        $manager->persist($product9);
        $product10 = (new Item())
            ->setCategory($this->getReference('category7', Category::class))
            ->setVariantOf(null)
            ->setTitle('Rosé')
            ->setLabel('🟠')
            ->setPrice(null)
            ->setColour('#ff7f50')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(10);
        $manager->persist($product10);
        $product11 = (new Item())
            ->setCategory($this->getReference('category7', Category::class))
            ->setVariantOf(null)
            ->setTitle('Rouge')
            ->setLabel('🔴')
            ->setPrice(null)
            ->setColour('#dc143c')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(11);
        $manager->persist($product11);
        $product12 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf(null)
            ->setTitle('Bouteille (50 cl)')
            ->setLabel('🍾')
            ->setPrice(15)
            ->setColour('#f38473')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(12);
        $manager->persist($product12);
        $product13 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf(null)
            ->setTitle('Ballon')
            ->setLabel('🍷')
            ->setPrice(3)
            ->setColour('#f38473')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(13);
        $manager->persist($product13);
        $product14 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product12)
            ->setTitle('Bouteille de blanc')
            ->setLabel('🍾🟡')
            ->setPrice(15)
            ->setColour('#fffacd')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(14);
        $manager->persist($product14);
        $product15 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product12)
            ->setTitle('Bouteille de rosé')
            ->setLabel('🍾🟠')
            ->setPrice(15)
            ->setColour('#ff7f50')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(15);
        $manager->persist($product15);
        $product16 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product12)
            ->setTitle('Bouteille de rouge')
            ->setLabel('🍾🔴')
            ->setPrice(15)
            ->setColour('#dc143c')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(16);
        $manager->persist($product16);
        $product17 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product13)
            ->setTitle('Ballon de blanc')
            ->setLabel('🍷🟡')
            ->setPrice(3)
            ->setColour('#fffacd')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(17);
        $manager->persist($product17);
        $product18 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product13)
            ->setTitle('Ballon de blanc + verre')
            ->setLabel('🍷🟡🥛')
            ->setPrice(5)
            ->setColour('#fffacd')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(18);
        $manager->persist($product18);
        $product19 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product13)
            ->setTitle('Ballon de rosé')
            ->setLabel('🍷🟠')
            ->setPrice(3)
            ->setColour('#ff7f50')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(19);
        $manager->persist($product19);
        $product20 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product13)
            ->setTitle('Ballon de rosé + verre')
            ->setLabel('🍷🟠🥛')
            ->setPrice(5)
            ->setColour('#ff7f50')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(20);
        $manager->persist($product20);
        $product21 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product13)
            ->setTitle('Ballon de rouge')
            ->setLabel('🍷🔴')
            ->setPrice(3)
            ->setColour('#dc143c')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(21);
        $manager->persist($product21);
        $product22 = (new Item())
            ->setCategory($this->getReference('category4', Category::class))
            ->setVariantOf($product13)
            ->setTitle('Ballon de rouge + verre')
            ->setLabel('🍷🔴🥛')
            ->setPrice(5)
            ->setColour('#dc143c')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(true)
            ->setPosition(22);
        $manager->persist($product22);
        $product23 = (new Item())
            ->setCategory($this->getReference('category2', Category::class))
            ->setVariantOf(null)
            ->setTitle('Café')
            ->setLabel('☕')
            ->setPrice(3)
            ->setColour('#330000')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(23);
        $manager->persist($product23);
        $product24 = (new Item())
            ->setCategory($this->getReference('category2', Category::class))
            ->setVariantOf(null)
            ->setTitle('Thé')
            ->setLabel('🍵')
            ->setPrice(3)
            ->setColour('#cc9040')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(24);
        $manager->persist($product24);
        $product25 = (new Item())
            ->setCategory($this->getReference('category1', Category::class))
            ->setVariantOf(null)
            ->setTitle('Gobelet consigné')
            ->setLabel('🥛')
            ->setPrice(2)
            ->setColour('#dcdcdc')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(25);
        $manager->persist($product25);
        $product26 = (new Item())
            ->setCategory($this->getReference('category8', Category::class))
            ->setVariantOf(null)
            ->setTitle('Verre à bierre et minérale (2,5 dl)')
            ->setLabel('🥛')
            ->setPrice(2)
            ->setColour('#dcdcdc')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(26);
        $manager->persist($product26);
        $product27 = (new Item())
            ->setCategory($this->getReference('category8', Category::class))
            ->setVariantOf(null)
            ->setTitle('Verre à vin (1 dl)')
            ->setLabel('🥛')
            ->setPrice(2)
            ->setColour('#dcdcdc')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(27);
        $manager->persist($product27);
        $product28 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Sandwich (jambon)')
            ->setLabel('🥪🐖')
            ->setPrice(5)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(false)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(28);
        $manager->persist($product28);
        $product29 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Pâté vaudois')
            ->setLabel('🥧')
            ->setPrice(5)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(false)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(29);
        $manager->persist($product29);
        $product30 = (new Item())
            ->setCategory($this->getReference('category9', Category::class))
            ->setVariantOf(null)
            ->setTitle('Tranche de porc')
            ->setLabel('🥩🐖')
            ->setPrice(11)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(false)
            ->setPosition(30);
        $manager->persist($product30);
        $product31 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Tranche de porc salade')
            ->setLabel('🥩🥬')
            ->setPrice(13)
            ->setColour('#00ffff')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(31);
        $manager->persist($product31);
        $product32 = (new Item())
            ->setCategory($this->getReference('category9', Category::class))
            ->setVariantOf(null)
            ->setTitle('Brochette de poulet')
            ->setLabel('🍗')
            ->setPrice(11)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(false)
            ->setSeparatelySellable(false)
            ->setPosition(32);
        $manager->persist($product32);
        $product33 = (new Item())
            ->setCategory($this->getReference('category10', Category::class))
            ->setVariantOf(null)
            ->setTitle('Salade')
            ->setLabel('🥬')
            ->setPrice(2)
            ->setColour('#ffffff')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(37);
        $manager->persist($product33);
        $product34 = (new Item())
            ->setCategory($this->getReference('category10', Category::class))
            ->setVariantOf(null)
            ->setTitle('Pain')
            ->setLabel('🍞')
            ->setPrice(0)
            ->setColour('#98fb98')
            ->setStock(null)
            ->setTicket(false)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(38);
        $manager->persist($product34);
        $product35 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Brochette de poulet salade')
            ->setLabel('🍗🥬')
            ->setPrice(13)
            ->setColour('#ff0000')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(35);
        $manager->persist($product35);
        $product36 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Saucisse')
            ->setLabel('🌭')
            ->setPrice(6)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(36);
        $manager->persist($product36);
        $product37 = (new Item())
            ->setCategory($this->getReference('category9', Category::class))
            ->setVariantOf(null)
            ->setTitle('Saucisse de veau')
            ->setLabel('🐄')
            ->setPrice(6)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(33);
        $manager->persist($product37);
        $product38 = (new Item())
            ->setCategory($this->getReference('category9', Category::class))
            ->setVariantOf(null)
            ->setTitle('Schublig')
            ->setLabel('🐖')
            ->setPrice(6)
            ->setColour(null)
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(false)
            ->setPosition(34);
        $manager->persist($product38);
        $product39 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf($product36)
            ->setTitle('Saucisse de veau salade')
            ->setLabel('🌭🐄🥬')
            ->setPrice(8)
            ->setColour('#ffffff')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(39);
        $manager->persist($product39);
        $product40 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf($product36)
            ->setTitle('Saucisse de veau pain')
            ->setLabel('🌭🐄🍞')
            ->setPrice(6)
            ->setColour('#98fb98')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(40);
        $manager->persist($product40);
        $product41 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf($product36)
            ->setTitle('Schublig salade')
            ->setLabel('🌭🐖🥬')
            ->setPrice(8)
            ->setColour('#ffffff')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(41);
        $manager->persist($product41);
        $product42 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf($product36)
            ->setTitle('Schublig pain')
            ->setLabel('🌭🐖🍞')
            ->setPrice(6)
            ->setColour('#98fb98')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(42);
        $manager->persist($product42);
        $product43 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Hot-dog')
            ->setLabel('🌭')
            ->setPrice(null)
            ->setColour('#ffc0cb')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(false)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(43);
        $manager->persist($product43);
        $product44 = (new Item())
            ->setCategory($this->getReference('category5', Category::class))
            ->setVariantOf(null)
            ->setTitle('Portion de frites')
            ->setLabel('🍟')
            ->setPrice(5)
            ->setColour('#ffd700')
            ->setStock(null)
            ->setTicket(true)
            ->setAvailable(true)
            ->setPublic(true)
            ->setSeparatelySellable(true)
            ->setPosition(44);
        $manager->persist($product44);

        $product6
            ->addComposedOf($product5)
            ->addComposedOf($product26);
        $product8
            ->addComposedOf($product7)
            ->addComposedOf($product26);
        $product14
            ->addComposedOf($product9);
        $product15
            ->addComposedOf($product10);
        $product16
            ->addComposedOf($product11);
        $product18
            ->addComposedOf($product17)
            ->addComposedOf($product25);
        $product20
            ->addComposedOf($product19)
            ->addComposedOf($product25);
        $product22
            ->addComposedOf($product21)
            ->addComposedOf($product25);
        $product31
            ->addComposedOf($product30)
            ->addComposedOf($product33);
        $product35
            ->addComposedOf($product32)
            ->addComposedOf($product33);
        $product39
            ->addComposedOf($product33)
            ->addComposedOf($product37);
        $product40
            ->addComposedOf($product34)
            ->addComposedOf($product37);
        $product41
            ->addComposedOf($product33)
            ->addComposedOf($product38);
        $product42
            ->addComposedOf($product34)
            ->addComposedOf($product38);

        $product12->addAttribute($this->getReference('category7', Category::class));
        $product13->addAttribute($this->getReference('category7', Category::class));
        $product25->addAttribute($this->getReference('category8', Category::class));
        $product36->addAttribute($this->getReference('category9', Category::class));
        $product36->addAttribute($this->getReference('category10', Category::class));

        $manager->flush();
    }
}
