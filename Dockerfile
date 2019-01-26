FROM php:apache

RUN apt-get update
RUN apt-get -y upgrade

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apt-get install -y libmemcached-dev

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev


RUN pecl install memcached \
    && docker-php-ext-enable memcached



RUN php -m | grep memcache

COPY code/ /var/www/html/


EXPOSE 80
