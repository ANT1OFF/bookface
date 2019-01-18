FROM php:5.6-apache

RUN apt-get update
RUN apt-get -y upgrade

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY code/ /var/www/html/


EXPOSE 80
