<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questiontag;
use App\Entity\Tag;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestiontagFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use App\Entity\Question;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(100);

      

        $questions = QuestionFactory::createMany(10,function(){
            return [
                'QuestionTags' => QuestiontagFactory::new(),
            ];
        });
        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);
        
        AnswerFactory::createMany(100,function() use($questions){
            return[
                'question'=>$questions[array_rand($questions)]
            ];
        });
        AnswerFactory::new(function() use($questions){
            return[
                'question'=>$questions[array_rand($questions)]
            ];
        })->needApproval()->many(20)->create();
        
        // $question = QuestionFactory::createOne();
        
        // $tag1 = new Tag();
        // $tag1->setName('abc');
        
        // $tag2 = new Tag();
        // $tag2->setName('pqr');
        
        // $question->addTag($tag1);
        // $question->addTag($tag2);

        // $manager->persist($tag1);
        // $manager->persist($tag2); 

        $manager->flush();
    }
}
