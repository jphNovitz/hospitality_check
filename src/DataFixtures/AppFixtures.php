<?php

namespace App\DataFixtures;

use App\Factory\ResidentFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures  extends Fixture implements FixtureGroupInterface
{
    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
//            UserFixtures::class,
//            ResidentFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        ResidentFactory::new()
            ->many(100)
            ->create(function() {
                return [
                    'referent' => UserFactory::random()
                ];
            });
        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['app_dev'];
    }
}
