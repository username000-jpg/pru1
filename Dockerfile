FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_pgsql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto
COPY . /var/www/html/

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala dependencias PHP (como dompdf)
RUN composer install --no-dev --optimize-autoloader

# Establece permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Expone el puerto para Apache
EXPOSE 80
