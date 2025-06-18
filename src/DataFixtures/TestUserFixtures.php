<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestUserFixtures extends Fixture
{
    private ?UserPasswordHasherInterface $passwordHasher = null;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userInactive = (new User())
            ->setName('inactive')
            ->setEnabled(false)
            ->setToken('inactiveToken')
            ->setCreatedAt(new \DateTimeImmutable());
        $hashedPassword = $this->passwordHasher->hashPassword($userInactive, 'inactive');
        $userInactive->setPassword($hashedPassword);
        $manager->persist($userInactive);

        $user = (new User())
            ->setName('user')
            ->setEnabled(true)
            ->setToken('userToken')
            ->setCreatedAt(new \DateTimeImmutable());
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $userAdmin = (new User())
            ->setName('admin')
            ->setEnabled(true)
            ->setRoles(['ROLE_ADMIN'])
            ->setToken('adminToken')
            ->setCreatedAt(new \DateTimeImmutable());
        $hashedPassword = $this->passwordHasher->hashPassword($userAdmin, 'admin');
        $userAdmin->setPassword($hashedPassword);
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
