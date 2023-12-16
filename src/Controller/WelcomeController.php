<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    #[Route('/welcome', name: 'app_welcome')]
    public function index(): JsonResponse
    {
        echo 123;
        return $this->json([
            'message' => 'Welcome to your new controller!',
        ]);
    }

    #[Route('/phpinfo', name: 'app_phpinfo')]
    public function phpinfo(): JsonResponse
    {
        phpinfo();
        return $this->json([
            'message' => 'Welcome to your new controller!',
        ]);
    }
}
