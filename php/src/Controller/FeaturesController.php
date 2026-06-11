<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FeaturesController extends AbstractController
{
    private string $configPath;

    public function __construct(#[Autowire('%kernel.project_dir%')] string $projectDir)
    {
        $this->configPath = $projectDir . '/config.json';
    }

    #[Route('/api/features', name: 'features_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse($this->loadFeatures());
    }

    #[Route('/api/features/toggle', name: 'features_toggle', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function toggle(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true) ?? [];
        $feature = $body['feature'] ?? null;
        $enabled = $body['enabled'] ?? null;

        if ($feature === null || !is_bool($enabled)) {
            return new JsonResponse(['error' => 'Missing or invalid fields: feature (string), enabled (bool)'], 422);
        }

        $config = $this->loadConfig();
        if (!array_key_exists($feature, $config['features'] ?? [])) {
            return new JsonResponse(['error' => "Unknown feature: {$feature}"], 404);
        }

        $config['features'][$feature] = $enabled;
        file_put_contents($this->configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return new JsonResponse(['feature' => $feature, 'enabled' => $enabled, 'message' => 'Feature updated successfully']);
    }

    private function loadFeatures(): array
    {
        return $this->loadConfig()['features'] ?? [];
    }

    private function loadConfig(): array
    {
        if (!file_exists($this->configPath)) {
            return ['features' => []];
        }
        return json_decode(file_get_contents($this->configPath), true) ?? [];
    }
}
