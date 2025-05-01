<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Code\Reflection\FunctionReflection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="app_homepage")
     */
    public function homepage(QuestionRepository $repositery)
    {
        /*
        // fun example of using the Twig service directly!
        $html = $twigEnvironment->render('question/homepage.html.twig');

        return new Response($html);
        */
        // $repositery = $entitymanager->getRepository(Question::class);
        $questions = $repositery->findAllAskedOrderedByNewest();
        return $this->render('question/homepage.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entitymanager)
    {
        $question = new Question();
        $question->setName('Missing pant')
            ->setSlug('missing-pen' . rand(0, 1000))
            ->setQuestion(
                'afkjawjraskviaerkffdwlfki'
            );
        if (rand(1, 10) > 2) {
            $question->setAsketAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }
        $entitymanager->persist($question);
        $entitymanager->flush();

        return new Response(sprintf(
            'well id #%d,slug %s',
            $question->getId(),
            $question->getSlug(),
        ));
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

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}
