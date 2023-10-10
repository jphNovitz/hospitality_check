<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ResidentFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
    }
}
