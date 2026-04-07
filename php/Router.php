<?php

private function dispatchAPI(): void {
    require_once __DIR__ . '/SessionHandler/SessionCheck.php';
    require_once __DIR__ . '/API/ConfigManager.php';

    if ($this->requestPath !== '/api/auth') {
        $token = $this->getBearerToken();
        if (!$token || !SessionCheck::validateToken($token)) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
    }

    $apiRoutes = [
        'POST' => [
            '/api/auth' => ['file' => 'API/auth', 'fn' => 'login', 'feature' => null],
            '/api/maintenance' => ['file' => 'API/maintenance', 'fn' => 'create', 'feature' => 'maintenance_requests'],
            '/api/rent/pay' => ['file' => 'API/rent', 'fn' => 'pay', 'feature' => 'rent_payment'],
            '/api/documents/upload' => ['file' => 'API/documents', 'fn' => 'upload', 'feature' => 'document_management'],
        ],
        'GET' => [
            '/api/dashboard' => ['file' => 'API/dashboard', 'fn' => 'index', 'feature' => null],
            '/api/features' => ['file' => 'API/dashboard', 'fn' => 'listFeatures', 'feature' => null],
            '/api/tenant' => ['file' => 'API/tenant', 'fn' => 'index', 'feature' => 'tenant_portal'],
            '/api/documents' => ['file' => 'API/documents', 'fn' => 'index', 'feature' => 'document_management'],
        ],
        'PATCH' => [
            '/api/features/toggle' => ['file' => 'API/dashboard', 'fn' => 'toggleFeature', 'feature' => null],
        ],
        'DELETE' => [],
    ];

    $routes = $apiRoutes[$this->requestMethod] ?? [];

    if (!isset($routes[$this->requestPath])) {
        $this->jsonResponse(['error' => 'API endpoint not found'], 404);
        return;
    }

    $route = $routes[$this->requestPath];

    if ($route['feature'] !== null && !ConfigManager::isEnabled($route['feature'])) {
        $this->jsonResponse([
            'error'   => 'Feature disabled',
            'feature' => $route['feature'],
        ], 403);
        return;
    }

    require_once __DIR__ . '/' . $route['file'] . '.php';
    ($route['fn'])();
}
