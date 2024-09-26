<?php

namespace App\DataFixtures;

use App\Entity\Base;
use App\Entity\Characteristic;
use App\Entity\Resident;
use App\Factory\ResidentFactory;
use App\Factory\UserFactory;
use App\Factory\CharacteristicFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            BaseFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $bases = $manager->getRepository(Base::class)->findAll();
        $residents = $manager->getRepository(Resident::class)->findAll();
        $characteristics = $manager->getRepository(Characteristic::class)->findAll();

        UserFactory::createMany(10);
        ResidentFactory::new()
            ->many(100)
            ->create(function () use ($bases, $characteristics) {
                return [
                    'referent' => UserFactory::random(),
                    'bases' => [
                        $bases[rand(0, count($bases) - 1)],
                        $bases[rand(0, count($bases) - 1)]
                    ],
                    'characteristics' => CharacteristicFactory::new()->many(rand(1, 5)),
                ];
            });;
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['app_dev'];
    }
}
