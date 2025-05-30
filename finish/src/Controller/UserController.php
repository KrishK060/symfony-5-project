<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * @Route("/api/me", name="app_user_api_me")
     * @isGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function apiMe(): Response
    {
        return $this->json($this->getUser(),200,[],[
            'groups' => ['user:read']
        ]);
    }
}
