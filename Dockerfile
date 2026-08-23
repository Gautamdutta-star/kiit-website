FROM php:8.2-apache

# Apache MPM conflict fix
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# Install MySQLi
RUN docker-php-ext-install mysqli

# Copy project files
COPY . /var/www/html/

EXPOSE 80
