FROM php:8.1-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer