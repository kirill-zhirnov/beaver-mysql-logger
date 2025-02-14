FROM composer/composer:latest-bin as composer

FROM php:8.4-alpine as php-server-basic
RUN apk add --update --no-cache openssl-dev linux-headers
RUN apk add --no-cache $PHPIZE_DEPS
RUN docker-php-ext-install pdo_mysql

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
WORKDIR /var/www/html

FROM php-server-basic as php-server-dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

CMD [ "php", "-S", "0.0.0.0:8080", "-t", "/var/www/html" ]

FROM php-server-basic as php-server-prod

#COPY composer.json composer.lock ./
#RUN composer install

COPY assets assets
COPY protected protected
COPY index.php index.php
RUN mkdir -p /var/www/html/runtime && chmod -R 0777 /var/www/html/runtime

CMD [ "php", "-S", "0.0.0.0:8080", "-t", "/var/www/html" ]