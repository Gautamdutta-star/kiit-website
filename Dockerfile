FROM php:8.2-apache

# Remove all conflicting Apache MPM modules
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
           /etc/apache2/mods-enabled/mpm_event.conf \
           /etc/apache2/mods-enabled/mpm_worker.load \
           /etc/apache2/mods-enabled/mpm_worker.conf

# Enable only prefork MPM
RUN a2enmod mpm_prefork

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Copy website files
COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
