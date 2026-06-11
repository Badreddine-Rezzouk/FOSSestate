<?php

declare(strict_types=1);

require_once __DIR__ . '/API/ConfigManager.php';

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $configPath = __DIR__ . '/config.json';
        if (!file_exists($configPath)) {
            throw new RuntimeException('config.json is required to connect to the database.');
        }

        $config = json_decode(file_get_contents($configPath), true);
        if (!is_array($config) || !isset($config['db'])) {
            throw new RuntimeException('Invalid config.json format.');
        }

        $db = $config['db'];
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $db['host'], $db['port'], $db['database']);
        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        self::$connection = $pdo;
        self::ensureSeedData();
        return self::$connection;
    }

    private static function ensureSeedData(): void
    {
        $pdo = self::$connection;
        $userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

        if ($userCount === 0) {
            $stmt = $pdo->prepare('INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)');
            $stmt->execute([
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'email' => 'admin@fossestate.local',
                'role' => 'admin',
            ]);
        }

        $propertyCount = (int) $pdo->query('SELECT COUNT(*) FROM properties')->fetchColumn();
        if ($propertyCount === 0) {
            $stmt = $pdo->prepare('INSERT INTO properties (name, property_type, address, city, postal_code, country, description) VALUES (:name, :property_type, :address, :city, :postal_code, :country, :description)');
            $stmt->execute([
                'name' => 'Central Plaza',
                'property_type' => 'apartment',
                'address' => '789 Plaza Blvd',
                'city' => 'Metropolis',
                'postal_code' => '12345',
                'country' => 'Countryland',
                'description' => 'Sample property for FOSSestate demo.',
            ]);
        }
    }
}
