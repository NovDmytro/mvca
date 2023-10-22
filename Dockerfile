FROM php:8.1-rc-apache-bookworm

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY docker /
RUN chown www-data:www-data -R /var/www; \
	chmod 1777 /var/www 
RUN rm -rf /var/www/html
RUN a2enmod rewrite