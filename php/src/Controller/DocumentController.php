<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DocumentController extends AbstractController
{
    #[Route('/api/documents', name: 'documents_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'documents' => [
                ['id' => 1, 'name' => 'Lease Agreement', 'status' => 'available'],
                ['id' => 2, 'name' => 'Property Inspection Report', 'status' => 'available'],
            ],
        ]);
    }

    #[Route('/api/documents/upload', name: 'documents_upload', methods: ['POST'])]
    public function upload(): JsonResponse
    {
        return new JsonResponse(['message' => 'Document upload endpoint accepted request.']);
    }
}
