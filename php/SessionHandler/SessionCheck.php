<?php

declare(strict_types=1);

class SessionCheck
{
    private static string $storeFile = __DIR__ . '/../token_store.json';
    private static ?array $cache = null;

    private static function loadTokens(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (!file_exists(self::$storeFile)) {
            file_put_contents(self::$storeFile, json_encode([]));
        }

        $data = json_decode((string) file_get_contents(self::$storeFile), true);
        self::$cache = is_array($data) ? $data : [];
        return self::$cache;
    }

    private static function saveTokens(array $tokens): void
    {
        file_put_contents(self::$storeFile, json_encode($tokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        self::$cache = $tokens;
    }

    public static function validateToken(string $token): bool
    {
        $tokens = self::loadTokens();
        return isset($tokens[$token]);
    }

    public static function storeToken(string $token, int $userId): bool
    {
        $tokens = self::loadTokens();
        $tokens[$token] = ['user_id' => $userId, 'created_at' => time()];
        self::saveTokens($tokens);
        return true;
    }

    public static function hasActiveSession(): bool
    {
        session_start();
        return isset($_SESSION['user_id']);
    }
}
?>
