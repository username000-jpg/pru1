# Imagen base con PHP + Apache
FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el proyecto completo al contenedor
COPY . /var/www/html/

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Ejecutar composer install
RUN composer install --no-dev --optimize-autoloader

# Asegurar permisos correctos (opcional pero recomendable)
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto de Apache
EXPOSE 80
