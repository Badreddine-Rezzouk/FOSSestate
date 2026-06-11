<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/api/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(): Response
    {
        // JWT is stateless; the client discards the token. Nothing to invalidate server-side.
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
