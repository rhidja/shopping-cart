<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@hidja.fr');
        $user->setFirstName('Admin');
        $user->setLastName('Admin');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPlainPassword('password');
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('rhidja@hidja.fr');
        $user->setFirstName('Ramtane');
        $user->setLastName('HIDJA');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_USER']);
        $user->setPlainPassword('password');
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $manager->persist($user);

        $manager->flush();
    }
}
