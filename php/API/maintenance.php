<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

function create(): void
{
    $body = loadJsonBody();

    $required = ['rental_id', 'created_by_user_id', 'title', 'description'];
    foreach ($required as $field) {
        if (empty($body[$field])) {
            jsonResponse(['error' => "Missing required field: {$field}"], 422);
        }
    }

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare(
        'INSERT INTO maintenance_requests (rental_id, created_by_user_id, title, description, priority, status) VALUES (:rental_id, :created_by_user_id, :title, :description, :priority, :status)'
    );

    $stmt->execute([
        'rental_id' => (int) $body['rental_id'],
        'created_by_user_id' => (int) $body['created_by_user_id'],
        'title' => trim($body['title']),
        'description' => trim($body['description']),
        'priority' => $body['priority'] ?? 'medium',
        'status' => 'open',
    ]);

    jsonResponse(['message' => 'Maintenance request created successfully']);
}
