<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Question;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        QuestionFactory::new()->createMany(10);
    }
}
