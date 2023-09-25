<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $user = new User();
       $user->setEmail('admin@test.be');
       $user->setRoles(['ROLE_ADMIN']);
       $user->setPassword('password');
        $manager->persist($user);
        $manager->flush();
    }
}
