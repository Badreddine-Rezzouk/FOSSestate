FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y libzip-dev libssl-dev libicu-dev zip unzip \
    && docker-php-ext-install pdo_mysql zip opcache intl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set document root to Symfony public/ and enable CORS headers
RUN printf '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    Header always set Access-Control-Allow-Origin "*"\n\
    Header always set Access-Control-Allow-Methods "GET, POST, PATCH, DELETE, OPTIONS"\n\
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization"\n\
    RewriteEngine On\n\
    RewriteCond %%{REQUEST_METHOD} OPTIONS\n\
    RewriteRule ^ - [R=204,L]\n\
</VirtualHost>\n' > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]
