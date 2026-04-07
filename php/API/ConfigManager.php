<?php

declare(strict_types=1);

class ConfigManager
{
    private static string $configPath = __DIR__ . "/../config.json";
    private static ?array $cache = null;

    private static function load(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (!file_exists(self::$configPath)) {
            throw new RuntimeException("config.json not found.");
        }

        $raw = file_get_contents(self::$configPath);
        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                "config.json is malformed: " . json_last_error_msg(),
            );
        }

        self::$cache = $decoded;
        return self::$cache;
    }

    private static function save(array $config): void
    {
        $json = json_encode(
            $config,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
        );

        $tmp = self::$configPath . ".tmp";
        file_put_contents($tmp, $json, LOCK_EX);
        rename($tmp, self::$configPath);

        self::$cache = $config;
    }

    public static function isEnabled(string $feature): bool
    {
        $config = self::load();
        return $config["features"][$feature]["enabled"] ?? false;
    }

    public static function getAllFeatures(): array
    {
        $config = self::load();
        return $config["features"] ?? [];
    }

    public static function setFeature(string $feature, bool $enabled): bool
    {
        $config = self::load();

        if (!isset($config["features"][$feature])) {
            return false;
        }

        $config["features"][$feature]["enabled"] = $enabled;
        self::save($config);
        return true;
    }
}
