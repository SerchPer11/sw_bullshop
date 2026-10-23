FROM php:8.4.27-apache

# Evitar que apt-get intente reconfigurar Apache interactivamente
ENV DEBIAN_FRONTEND=noninteractive

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

# ELIMINAR EL CONFLICTO DE MPM FÍSICAMENTE Y HABILITAR EL CORRECTO
RUN rm -f /etc/apache2/mods-enabled/mpm_*.conf /etc/apache2/mods-enabled/mpm_*.load \
    && a2enmod mpm_prefork rewrite

# Copiar Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar dependencias de PHP y NPM
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Apuntar Apache a la carpeta public
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Arranque limpio nativo
CMD ["apache2-foreground"]