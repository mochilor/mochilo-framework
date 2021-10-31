FROM composer:1.9.3 as vendor

WORKDIR /tmp/

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM php:7.4.25-fpm-alpine

RUN apk add --no-cache git zip unzip gnupg

COPY . /app
COPY --from=vendor /tmp/vendor/ /app/vendor/
WORKDIR /app
