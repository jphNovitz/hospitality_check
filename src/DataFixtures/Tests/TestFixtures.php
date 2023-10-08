<?php

namespace App\DataFixtures\Tests;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [
            UserTestFixtures::class,
            ResidentTestFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
    }
}
