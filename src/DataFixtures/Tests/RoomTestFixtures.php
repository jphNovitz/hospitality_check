<?php

namespace App\DataFixtures\Tests;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\ResidentPasswordHasherInterface;

class RoomTestFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $room = new Room();
            $room->setNumber($i);
            $manager->persist($room);
        }

        $manager->flush();
    }
}
