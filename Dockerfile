# Imagen base de PHP con Apache
FROM php:8.2-apache

# Instala herramientas necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia todo el código fuente al contenedor
COPY . /var/www/html/

# Define el directorio de trabajo
WORKDIR /var/www/html

# Instala las dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Establece permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Expone el puerto de Apache
EXPOSE 80
