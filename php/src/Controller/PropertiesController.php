<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PropertiesController extends AbstractController
{
    #[Route('/api/properties', name: 'properties_index', methods: ['GET'])]
    public function index(PropertyRepository $repo): JsonResponse
    {
        $properties = $repo->findAllWithTenantCount();

        return new JsonResponse(['properties' => $properties]);
    }
}
