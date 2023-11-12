FROM php:8.2-fpm

COPY php.ini /usr/local/etc/php/

# Install system dependencies
RUN apt-get update && apt-get install -y \
    cron \
    curl \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install zip intl pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/api

copy . .

RUN chown -R www-data:www-data /var/www/api