FROM php:7.3-fpm-alpine

COPY ./php.ini $PHP_INI_DIR/php.ini

RUN docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) pdo_mysql
