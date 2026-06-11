<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

class Router
{
    private string $requestPath;
    private string $requestMethod;

    public function __construct()
    {
        $this->requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $this->requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function dispatch(): void
    {
        if (str_starts_with($this->requestPath, '/api/')) {
            $this->dispatchAPI();
            return;
        }

        $this->serveStatus();
    }

    private function dispatchAPI(): void
    {
        require_once __DIR__ . '/SessionHandler/SessionCheck.php';
        require_once __DIR__ . '/API/ConfigManager.php';
        require_once __DIR__ . '/Database.php';

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

        if (!isset($apiRoutes[$this->requestMethod][$this->requestPath])) {
            jsonResponse(['error' => 'API endpoint not found'], 404);
        }

        $route = $apiRoutes[$this->requestMethod][$this->requestPath];

        if ($route['feature'] !== null && !ConfigManager::isEnabled($route['feature'])) {
            jsonResponse(['error' => 'Feature disabled', 'feature' => $route['feature']], 403);
        }

        if ($this->requestPath !== '/api/auth') {
            $token = getBearerToken();
            if (!$token || !SessionCheck::validateToken($token)) {
                jsonResponse(['error' => 'Unauthorized'], 401);
            }
        }

        require_once __DIR__ . '/' . $route['file'] . '.php';
        ($route['fn'])();
    }

    private function serveStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'FOSSestate API is running',
            'apiBase' => '/api',
            'documentation' => 'Use /api/* routes to interact with the server',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }
}
