FROM php:8.1-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql

COPY ./backend .

RUN composer install