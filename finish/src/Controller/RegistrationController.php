<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelper;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private RouterInterface $route;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator,FormLoginAuthenticator $formLoginAuthenticator, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // dd($user);
            $entityManager->persist($user);
            $entityManager->flush();
            
            // $userAuthenticator->authenticateUser(
            //     $user,
            //     $formLoginAuthenticator,
            //     $request
            // );
        $signatureComponents = $verifyEmailHelper->generateSignature(
            'app_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id'=>$user->getId()]
        );
        
        $this->addFlash('sucess','confirm your email at :'.$signatureComponents->getSignedUrl());

        return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/verify", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper,UserRepository $userRepository,EntityManagerInterface $entityManager){
        $user = $userRepository->find($request->query->get('id'));
        if(!$user){
            throw $this->createNotFoundException();
        }

        try{
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );
        }catch(VerifyEmailExceptionInterface $e){
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_register');

        }
        $user->setIsVerified(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/verify/resend",name = "app_verify_resend_email")
     */
    public function resendVerifyEmail(){
        return $this->render('registration/resend_verify_email.html.twig');
    }
}
