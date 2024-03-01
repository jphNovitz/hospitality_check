<?php

namespace App\DataFixtures\Tests;

use App\Entity\Resident;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\ResidentPasswordHasherInterface;
use function Symfony\Component\Clock\now;

class ResidentTestFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $rooms = $manager->getRepository(Room::class)
            ->findAll();
        $user = $manager->getRepository(User::class)
            ->findOneBy(['email' => "referent@exempl.es"]);

        $resident = new Resident();
        $resident->setFirstName('Cruchot');
        $resident->setBirthDate(new \DateTimeImmutable('2010/06/06'));
        $resident->setNationality('Belche');
        $resident->setRoom($rooms[1]);
        $resident->setReferent($user);

        $manager->persist($resident);

        $manager->flush();
    }
}
