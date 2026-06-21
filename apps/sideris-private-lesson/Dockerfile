# Menggunakan image PHP 8.3 dengan Apache
FROM php:8.3-apache

# Set variabel agar Composer bisa dijalankan sebagai root/superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip git nodejs npm \
    && docker-php-ext-install pdo_mysql gd zip

# Menginstal Composer secara global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file dari repo ke dalam folder aplikasi di container
COPY . /var/www/html/apps/sideris-private-lesson

# Tentukan direktori kerja ke folder aplikasi
WORKDIR /var/www/html/apps/sideris-private-lesson

# Jalankan composer install
RUN composer install --no-dev --optimize-autoloader

# Install Vite dependencies dan Build aset
RUN npm install && npm run build

# Berikan izin akses folder storage dan cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Konfigurasi Apache agar mengarah ke folder public Laravel
RUN sed -i 's|/var/www/html|/var/www/html/apps/sideris-private-lesson/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Perbaikan error "More than one MPM loaded"
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Expose port 80 untuk web
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]