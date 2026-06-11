#!/bin/bash
set -e

JWT_DIR=/var/www/html/config/jwt

# Generate JWT RSA key pair if not present
if [ ! -f "$JWT_DIR/private.pem" ]; then
    echo "[entrypoint] Generating JWT key pair..."
    mkdir -p "$JWT_DIR"
    openssl genpkey -algorithm RSA -out "$JWT_DIR/private.pem" -pkeyopt rsa_keygen_bits:4096 2>/dev/null
    openssl rsa -pubout -in "$JWT_DIR/private.pem" -out "$JWT_DIR/public.pem" 2>/dev/null
    echo "[entrypoint] JWT keys generated."
fi

# Install Composer dependencies if vendor/ is missing
if [ ! -d /var/www/html/vendor ]; then
    echo "[entrypoint] Installing Composer dependencies..."
    cd /var/www/html
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Ensure var/ dirs are writable
mkdir -p /var/www/html/var/cache /var/www/html/var/log
chmod -R 777 /var/www/html/var

# Wait for MySQL to accept connections
echo "[entrypoint] Waiting for MySQL..."
until php -r "new PDO('mysql:host=mysql;port=3306;dbname=foss_estate;charset=utf8mb4', 'foss', 'foss_password');" 2>/dev/null; do
    sleep 2
done
echo "[entrypoint] MySQL is ready."

# Seed demo data if the users table is empty
php -r "
\$pdo = new PDO('mysql:host=mysql;port=3306;dbname=foss_estate;charset=utf8mb4', 'foss', 'foss_password', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
if ((int)\$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
    \$hash = password_hash('admin123', PASSWORD_DEFAULT);
    \$pdo->prepare('INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)')->execute(['admin', \$hash, 'admin@fossestate.local', 'admin']);
    \$pdo->prepare('INSERT INTO properties (name, property_type, address, city, postal_code, country, description) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute(['Central Plaza', 'apartment', '789 Plaza Blvd', 'Metropolis', '12345', 'Countryland', 'Sample property for FOSSestate demo.']);
    echo '[entrypoint] Database seeded with demo data.' . PHP_EOL;
}
" 2>/dev/null || true

# Warm up Symfony cache
cd /var/www/html
php bin/console cache:warmup --no-debug 2>/dev/null || true

exec apache2-foreground
