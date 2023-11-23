FROM php:8.2-fpm

COPY php.ini /usr/local/etc/php/

# RUN groupadd -g 1001 php && useradd -u 1001 -g php -m php

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

# RUN chown -R php:php /var/www/api

# USER php