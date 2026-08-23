FROM php:8.2-apache

RUN a2dismod mpm_event mpm_worker || true

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html

COPY . /var/www/html/

EXPOSE 80
