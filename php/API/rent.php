<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

function pay(): void
{
    $body = loadJsonBody();

    if (empty($body['lease_id']) || empty($body['amount']) || empty($body['payment_date'])) {
        jsonResponse(['error' => 'lease_id, amount and payment_date are required'], 422);
    }

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare(
        'INSERT INTO payments (lease_id, amount, payment_date, payment_method, status) VALUES (:lease_id, :amount, :payment_date, :payment_method, :status)'
    );

    $stmt->execute([
        'lease_id' => (int) $body['lease_id'],
        'amount' => (float) $body['amount'],
        'payment_date' => $body['payment_date'],
        'payment_method' => $body['payment_method'] ?? 'bank_transfer',
        'status' => 'paid',
    ]);

    jsonResponse(['message' => 'Payment recorded successfully']);
}
