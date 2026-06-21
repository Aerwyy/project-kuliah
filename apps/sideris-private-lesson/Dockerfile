# Menggunakan image PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev zip unzip git nodejs npm \
    && docker-php-ext-install pdo_mysql gd zip

# Menginstal Composer secara global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tentukan direktori kerja utama di dalam container
WORKDIR /var/www/html

# Copy semua file dari repo ke dalam container
COPY . .

# Set working directory ke folder aplikasi laravel kamu
WORKDIR /var/www/html/apps/sideris-private-lesson

# Jalankan composer dengan mengarahkan ke path yang benar
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html/apps/sideris-private-lesson

# Install Vite dependencies dan Build aset
RUN npm install && npm run build

# Berikan izin akses folder storage dan cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Konfigurasi Apache agar mengarah ke folder public Laravel
RUN sed -i 's|/var/www/html|/var/www/html/apps/sideris-private-lesson/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Expose port 80 untuk web
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]