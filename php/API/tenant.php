<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

function index(): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->query(
        'SELECT t.id, u.username, u.email, t.first_name, t.last_name, t.phone, t.emergency_contact, t.created_at
         FROM tenants t
         JOIN users u ON u.id = t.user_id
         ORDER BY t.created_at DESC'
    );

    $tenants = $stmt->fetchAll();
    jsonResponse(['tenants' => $tenants]);
}
