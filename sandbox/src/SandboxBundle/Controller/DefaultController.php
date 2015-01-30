<?php

namespace SandboxBundle\Controller;

use SandboxBundle\Domain\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/register", name="homepage")
     */
    public function registerAction(Request $request)
    {
        $username = $request->query->get('username');
        if (!$username) {
            return new Response('Invalid username', 400);
        }

        $userRepository = $this->container->get('user_repository');
        $userId = rand();
        $userRepository->add(new User($userId, $username));

        $data = array('user_id' => $userId);

        return new JsonResponse($data);
    }
}
