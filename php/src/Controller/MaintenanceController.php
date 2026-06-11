<?php

namespace App\Controller;

use App\Entity\MaintenanceRequest;
use App\Entity\Rental;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MaintenanceController extends AbstractController
{
    #[Route('/api/maintenance', name: 'maintenance_list', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $conn = $em->getConnection();
        $rows = $conn->fetchAllAssociative(
            'SELECT m.id, m.title, m.description, m.priority, m.status, m.created_at,
                    r.title AS rental_title, u.username AS created_by
             FROM maintenance_requests m
             JOIN rentals r ON r.id = m.rental_id
             JOIN users u ON u.id = m.created_by_user_id
             ORDER BY m.created_at DESC'
        );

        return new JsonResponse(['maintenance_requests' => $rows]);
    }

    #[Route('/api/maintenance', name: 'maintenance_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $body = json_decode($request->getContent(), true) ?? [];

        foreach (['rental_id', 'title'] as $field) {
            if (empty($body[$field])) {
                return new JsonResponse(['error' => "Missing required field: {$field}"], 422);
            }
        }

        $rental = $em->find(Rental::class, (int) $body['rental_id']);
        if (!$rental) {
            return new JsonResponse(['error' => 'Rental not found'], 404);
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $mr = new MaintenanceRequest();
        $mr->setRental($rental);
        $mr->setCreatedByUser($currentUser);
        $mr->setTitle(trim($body['title']));
        $mr->setDescription(isset($body['description']) ? trim($body['description']) : null);
        $mr->setPriority($body['priority'] ?? 'medium');
        $mr->setStatus('open');

        $em->persist($mr);
        $em->flush();

        return new JsonResponse(['message' => 'Maintenance request created successfully'], 201);
    }
}
