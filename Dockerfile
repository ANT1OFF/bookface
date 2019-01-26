FROM php:apache

RUN apt-get update
RUN apt-get -y upgrade


RUN apt-get update \
        && buildDeps=" \
                git \
                libmemcached-dev \
                zlib1g-dev \
        " \
        && doNotUninstall=" \
                libmemcached11 \
                libmemcachedutil2 \
        " \
        && apt-get install -y $buildDeps --no-install-recommends \
        && rm -r /var/lib/apt/lists/* \
        \
        && docker-php-source extract \
        && git clone --branch php7 https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached/ \
        && docker-php-ext-install memcached \
        \
        && docker-php-source delete \
        && apt-mark manual $doNotUninstall \
        && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false $buildDeps



RUN php -m | grep memcache

COPY code/ /var/www/html/


EXPOSE 80
