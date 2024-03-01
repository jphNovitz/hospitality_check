<?php

namespace App\DataFixtures\Tests;

use App\Entity\Base;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BaseTestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i= 0 ; $i < 1 ; $i++){
            $base = new Base();
            $base->setName('Basic_'.$i);
            $base->setDescription('Description '.$i.' Lorem impsum');

             $manager->persist($base);
        }

        $manager->flush();
    }
}
