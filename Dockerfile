FROM php:apache

RUN apt-get update
RUN apt-get -y upgrade

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
    && rm /tmp/memcached.tar.gz

RUN php -m | grep memcache

COPY code/ /var/www/html/


EXPOSE 80
