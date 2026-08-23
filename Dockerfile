FROM php:8.2-apache

RUN a2dismod mpm_event mpm_worker mpm_prefork || true \
    && a2enmod mpm_prefork

WORKDIR /var/www/html

COPY . /var/www/html/

RUN docker-php-ext-install mysqli pdo pdo_mysql

EXPOSE 80

CMD ["apache2-foreground"]
