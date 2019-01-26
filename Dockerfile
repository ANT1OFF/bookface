FROM php:apache

RUN apt-get update
RUN apt-get -y upgrade

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev

RUN apt-get update && \
    apt-get install -y \
        git


RUN apt-get install -y libmemcached-dev \
 && cd /tmp \
 && git clone -b php7 https://github.com/php-memcached-dev/php-memcached.git \
 && cd php-memcached \
 && phpize \
 && ./configure \
 && make \
 && echo "extension=/tmp/php-memcached/modules/memcached.so" > /usr/local/etc/php/conf.d/memcached.ini



RUN php -m | grep memcache

COPY code/ /var/www/html/


EXPOSE 80
