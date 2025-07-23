
# Imagen base con Apache y PHP
FROM php:8.1-apache

# Instala extensiones necesarias (PostgreSQL)
RUN apt-get update && \
    apt-get install -y libpq-dev unzip git && \
    docker-php-ext-install pdo pdo_pgsql

# Copia tu código al contenedor
COPY . /var/www/html/

# Da permisos a la carpeta del proyecto
RUN chown -R www-data:www-data /var/www/html

# Habilita mod_rewrite si lo necesitas
RUN a2enmod rewrite
