FROM php:8.2-apache

# Pindahkan ENV ke atas agar terbaca oleh perintah RUN selanjutnya
ENV COMPOSER_ALLOW_SUPERUSER=1

# ... (Instalasi dependencies sistem tetap sama) ...

# Salin semua file ke folder tujuan langsung
COPY . /var/www/html/apps/sideris-private-lesson

# Tentukan direktori kerja
WORKDIR /var/www/html/apps/sideris-private-lesson

# Baru jalankan composer (karena file sudah ada di folder ini)
RUN composer install --no-dev --optimize-autoloader

# Install Vite dependencies dan Build aset
RUN npm install && npm run build

# Berikan izin akses folder storage dan cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Konfigurasi Apache agar mengarah ke folder public Laravel
RUN sed -i 's|/var/www/html|/var/www/html/apps/sideris-private-lesson/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
ENV COMPOSER_ALLOW_SUPERUSER=1

# Expose port 80 untuk web
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]