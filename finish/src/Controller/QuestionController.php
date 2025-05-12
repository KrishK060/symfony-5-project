<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Code\Reflection\FunctionReflection;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    /**
     * @Route("/{page<\d+>}", name="app_homepage")
     */
    public function homepage(QuestionRepository $repositery,int $page = 1)
    {
        /*
        // fun example of using the Twig service directly!
        $html = $twigEnvironment->render('question/homepage.html.twig');

        return new Response($html);
        */
        // $repositery = $entitymanager->getRepository(Question::class);
        $queryBuilder = $repositery->createAskedOrderedByNewestQueryBuilder();

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($queryBuilder)
        );

        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        return $this->render('question/homepage.html.twig', [
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entitymanager)
    {
        // $question = new Question();
        // $question->setName('Missing pant')
        //     ->setSlug('missing-pen' . rand(0, 1000))
        //     ->setQuestion(
        //         'afkjawjraskviaerkffdwlfki'
        //     );
        // if (rand(1, 10) > 2) {
        //     $question->setAsketAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        // }
        // $question->setVotes(rand(-20, 30));
        // $entitymanager->persist($question);
        // $entitymanager->flush();

        // return new Response(sprintf(
        //     'well id #%d,slug %s',
        //     $question->getId(),
        //     $question->getSlug(),
        // ));
        return new Response('sounds like a great');
    }
    /**
 * @Route("/questions/{slug}", name="app_question_show")
 */
public function show(Question $question)

    {
        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];
        $answers = $question->getAnswers();
        foreach($answers as $answer){
            dump($answer);

        }
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request,EntityManagerInterface $entitymanager )
    {
         $direction = $request->request->all();

        //  dd($direction);
         if ($direction['direction'] === 'up') {
             $question->setVotes($question->getVotes() + 1);
             //  dd($direction);
        } else if ($direction['direction'] === 'down') {
            $question->setVotes($question->getVotes() - 1);
        }
        $entitymanager->flush();
        return $this->redirectToRoute('app_question_show',[
            'slug'=>$question->getSlug(),
        ]);
    }
}
