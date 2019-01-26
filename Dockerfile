FROM php:apache

RUN apt-get update
RUN apt-get -y upgrade

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install memcached

RUN php -m | grep memcache

COPY code/ /var/www/html/


EXPOSE 80
