FROM php:8.1-apache

# Instala extensiones necesarias (PostgreSQL + utilidades)
RUN apt-get update && \
    apt-get install -y libpq-dev unzip git && \
    docker-php-ext-install pdo pdo_pgsql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia tu código al contenedor
COPY . /var/www/html/

# Establece directorio de trabajo
WORKDIR /var/www/html

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Da permisos a la carpeta del proyecto
RUN chown -R www-data:www-data /var/www/html

# Habilita mod_rewrite si lo necesitas
RUN a2enmod rewrite

