<?php

namespace App\Controller;

use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answers/popular", name="app_popular_answers")
     */
    public function popularAnswers(AnswerRepository $answerRepository,Request $request): Response
    {
        $answers = $answerRepository->findMostPopular(
            $request->query->get('q') 
        );
        return $this->render('answer/popularAnswers.html.twig', [
            'answers' => $answers,
        ]);
        //     'controller_name' => 'AnswerController',
        // ]);
    }
}
