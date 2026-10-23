FROM php:8.4.27-apache

# Instalar dependencias del sistema y Node.js 22
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP para Laravel y PostgreSQL
RUN docker-php-ext-install pdo_pgsql pdo_mysql bcmath zip

# Habilitar URLs limpias en Apache
RUN a2enmod rewrite

# Copiar Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar dependencias de PHP y NPM
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Configurar permisos críticos para Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Apuntar Apache a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/app/!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# Comando de inicio: Limpia caché de Laravel, purga MPMs conflictivos y arranca Apache
CMD ["bash", "-c", " \
    php artisan config:cache && \
    php artisan route:cache && \
    a2dismod mpm_event mpm_worker || true && \
    rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true && \
    a2enmod mpm_prefork && \
    exec apache2-foreground \
"]