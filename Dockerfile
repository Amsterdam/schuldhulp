FROM composer:1.5
FROM php:7.1.14-fpm-alpine

EXPOSE 80

RUN apk update \
 && apk upgrade

RUN apk add bash

RUN apk add nginx \
 && mkdir /run/nginx

COPY --from=composer:1.5 /usr/bin/composer /usr/bin/composer

#COPY Docker/nginx/nginx.conf /usr/local/etc/nginx/nginx.conf
#COPY Docker/nginx/vhost.conf /usr/local/etc/nginx/conf.d/default.conf
#COPY Docker/php/php.ini /usr/local/etc/php/php.ini

WORKDIR /
RUN mkdir /srv/app
COPY . /srv/app
WORKDIR /srv/app

ENV COMPOSER_ALLOW_SUPERUSER 1

ENTRYPOINT /srv/app/docker-entrypoint.sh

#RUN composer install --no-interaction --no-suggest --prefer-dist \
# && php bin/console cache:clear --env=prod \
# && php bin/console cache:clear --env=dev \
# && chown -R www-data:www-data var

