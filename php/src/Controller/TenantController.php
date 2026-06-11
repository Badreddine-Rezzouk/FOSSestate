<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TenantController extends AbstractController
{
    #[Route('/api/tenant', name: 'tenant_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $rows = $em->getConnection()->fetchAllAssociative(
            'SELECT t.id, u.username, u.email, t.first_name, t.last_name, t.phone, t.emergency_contact, t.created_at
             FROM tenants t
             JOIN users u ON u.id = t.user_id
             ORDER BY t.created_at DESC'
        );

        return new JsonResponse(['tenants' => $rows]);
    }
}
