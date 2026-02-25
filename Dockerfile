//FROM node:20-alpine AS assets
//WORKDIR /app
//COPY package*.json ./
//RUN npm ci

//COPY . .
//RUN  npm run build

//FROM composer:2 AS vendor
//WORKDIR /app
//COPY composer.json composer.lock ./
//RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

//FROM dunglas/frankenphp:latest
//WORKDIR /app
//RUN apt-get update && apt-get install -y unzip git && rm -rf /var/lib/apt/lists/*

//COPY . .
//COPY --from=vendor /app/vendor /app/vendor
//COPY --from=assets /app/public/build /app/public/build

//RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

//EXPOSE 8000

//CMD "php","artisan","octane:frankenphp","--host=0.0.0.0","--port=8000","--workers=2","--max-requests=300"

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

# Dependencias necesarias para intl y zip
RUN apk add --no-cache icu-dev libzip-dev

# Instalar extensiones PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

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

EXPOSE 8000

CMD ["php-fpm"]