FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Run as the host user to avoid permission issues with mounted volumes
ARG UID=1000
ARG GID=1000
RUN groupmod -g ${GID} www-data 2>/dev/null || true \
    && usermod -u ${UID} -g ${GID} www-data 2>/dev/null || true

EXPOSE 9000
CMD ["php-fpm"]
