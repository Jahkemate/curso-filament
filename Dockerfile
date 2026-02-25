########################################
# 1️⃣ STAGE - Build de assets (Vite)
########################################
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copiar solo package.json primero (mejor cache)
COPY package*.json ./

RUN npm install

# Copiar el resto del proyecto
COPY . .

# Compilar assets
RUN npm run build


########################################
# 2️⃣ STAGE - PHP Production
########################################
FROM php:8.4-fpm-alpine

# Instalar dependencias del sistema
RUN apk add --no-cache \
    bash \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar proyecto
COPY . .

# Copiar assets compilados desde node_builder
COPY --from=node_builder /app/public/build ./public/build

# Instalar dependencias PHP (sin dev)
RUN composer install --no-dev --optimize-autoloader

# Optimizar Laravel
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Permisos correctos
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]