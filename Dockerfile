FROM php:8.3-apache

# 1. Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql bcmath

# 2. Habilitar el módulo de reescritura de Apache (Crucial para las rutas de Laravel)
RUN a2enmod rewrite

# 3. Cambiar la raíz del servidor Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Copiar los archivos del proyecto al contenedor
WORKDIR /var/www/html
COPY . .

# 5. Instalar Composer de forma interna
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. Permisos correctos para que Laravel pueda escribir logs y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Exponer el puerto por defecto
EXPOSE 80