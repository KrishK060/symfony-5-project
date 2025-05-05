<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use App\Entity\Question;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        QuestionFactory::new()->createMany(10);
        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);
        
        $answer = new Answer();
        $answer->setContent("this is the question");
        $answer->setUsername('heaven');

        $question = new Question();
        $question->setName('how to disappear');
        $question->setQuestion('i sould not have this');

        $manager->persist($answer);
        $manager->persist($question);
        $manager->flush();
    }
}
