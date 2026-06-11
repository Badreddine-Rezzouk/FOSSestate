<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/api/health', name: 'health', methods: ['GET'])]
    public function health(): JsonResponse
    {
        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/api/dashboard', name: 'dashboard_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $conn = $em->getConnection();

        $totalProperties = (int) $conn->fetchOne('SELECT COUNT(*) FROM properties');
        $activeTenants = (int) $conn->fetchOne('SELECT COUNT(*) FROM tenants');
        $openMaintenance = (int) $conn->fetchOne(
            "SELECT COUNT(*) FROM maintenance_requests WHERE status IN ('open', 'in_progress')"
        );
        $monthlyRevenue = (float) ($conn->fetchOne(
            "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'paid' AND MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())"
        ) ?? 0);

        return new JsonResponse([
            'status' => 'ok',
            'stats' => [
                'total_properties' => $totalProperties,
                'active_tenants' => $activeTenants,
                'open_maintenance' => $openMaintenance,
                'monthly_revenue' => $monthlyRevenue,
            ],
        ]);
    }
}
