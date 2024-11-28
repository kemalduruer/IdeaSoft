FROM php:7.4-fpm

# Gerekli bağımlılıkları yükle
RUN apt-get update && apt-get install -y \
    curl \
    libcurl4-openssl-dev \
    libonig-dev \
    libxml2-dev \
    git \
    zip \
    unzip

# PHP eklentilerini yükle
RUN   docker-php-ext-install curl json mbstring mysqli intl pdo pdo_mysql tokenizer xml
RUN   docker-php-ext-enable curl json mbstring mysqli intl pdo pdo_mysql tokenizer xml

# Composer'ı yükle
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravel projesini oluştur veya var olanı kopyala
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate

# Yazılabilir dizin izinlerini ayarla
RUN chmod -R 777 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Portları aç
EXPOSE 80
