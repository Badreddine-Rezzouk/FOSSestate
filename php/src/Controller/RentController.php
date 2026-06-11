<?php

namespace App\Controller;

use App\Entity\Lease;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RentController extends AbstractController
{
    #[Route('/api/rent/pay', name: 'rent_pay', methods: ['POST'])]
    public function pay(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $body = json_decode($request->getContent(), true) ?? [];

        if (empty($body['lease_id']) || empty($body['amount']) || empty($body['payment_date'])) {
            return new JsonResponse(['error' => 'lease_id, amount and payment_date are required'], 422);
        }

        $lease = $em->find(Lease::class, (int) $body['lease_id']);
        if (!$lease) {
            return new JsonResponse(['error' => 'Lease not found'], 404);
        }

        $payment = new Payment();
        $payment->setLease($lease);
        $payment->setAmount((string) $body['amount']);
        $payment->setPaymentDate(new \DateTimeImmutable($body['payment_date']));
        $payment->setPaymentMethod($body['payment_method'] ?? 'bank_transfer');
        $payment->setStatus('paid');

        $em->persist($payment);
        $em->flush();

        return new JsonResponse(['message' => 'Payment recorded successfully'], 201);
    }
}
