<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../SessionHandler/SessionCheck.php';

function login(): void
{
    $body = loadJsonBody();
    $username = trim($body['username'] ?? '');
    $password = $body['password'] ?? '';

    if ($username === '' || $password === '') {
        jsonResponse(['error' => 'Missing username or password'], 422);
    }

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(['error' => 'Invalid credentials'], 401);
    }

    $token = bin2hex(random_bytes(32));
    SessionCheck::storeToken($token, (int) $user['id']);

    jsonResponse(['token' => $token, 'message' => 'Login successful']);
}
