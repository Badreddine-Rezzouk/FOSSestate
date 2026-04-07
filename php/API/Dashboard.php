<?php

declare(strict_types=1);

require_once __DIR__ . "/ConfigManager.php";

function index(): void
{
    jsonResponse(["status" => "ok"]);
}

function listFeatures(): void
{
    jsonResponse(ConfigManager::getAllFeatures());
}

function toggleFeature(): void
{
    $body = json_decode(file_get_contents("php://input"), true);

    $feature = $body["feature"] ?? null;
    $enabled = $body["enabled"] ?? null;

    if ($feature === null || !is_bool($enabled)) {
        jsonResponse(
            [
                "error" =>
                    "Missing or invalid fields: feature (string), enabled (bool)",
            ],
            422,
        );
        return;
    }

    $success = ConfigManager::setFeature($feature, $enabled);

    if (!$success) {
        jsonResponse(["error" => "Unknown feature: {$feature}"], 404);
        return;
    }

    jsonResponse([
        "feature" => $feature,
        "enabled" => $enabled,
        "message" => "Feature updated successfully",
    ]);
}

function jsonResponse(array $data, int $status = 200): void
{
    http_response_code($status);
    header("Content-Type: application/json");
    echo json_encode($data);
    exit();
}
