# syntax=docker/dockerfile:1
FROM php:apache

RUN apt update \
    && apt -y upgrade \
    && docker-php-ext-install iconv && docker-php-ext-enable iconv \
    && a2enmod rewrite

WORKDIR /var/www/html
ADD 

EXPOSE 80/tcp
EXPOSE 443/tcp

#TAG gcplength-saas-php
