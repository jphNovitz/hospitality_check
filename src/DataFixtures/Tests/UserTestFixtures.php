<?php

namespace App\DataFixtures\Tests;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTestFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@test.be');
        $user->setName('Jean-Francois Ipsum');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);
        $user = new User();
        $user->setEmail('user_1@test.be');
        $user->setName('User One');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);
        $user = new User();
        $user->setEmail('user_2@test.be');
        $user->setName('User Two');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);
        $manager->flush();
    }
}
