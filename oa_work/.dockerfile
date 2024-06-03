FROM php:7.4.23-fpm-alpine

MAINTAINER Mike1

RUN apk update && apk add make zlib-dev gcc g++ nginx autoconf
RUN apk --no-cache add graphicsmagick zip libjpeg-turbo-dev libpng-dev freetype-dev imagemagick-dev libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN  docker-php-ext-install gd
#composer安装
# RUN set -x \
#     && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin \
#     && mv /usr/local/bin/composer.phar /usr/local/bin/composer

RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

COPY ./ /app

COPY ./docker/* /tmp/docker/

COPY ./docker/default.conf /etc/nginx/http.d/default.conf

RUN chmod -R 755 /tmp/docker/

WORKDIR /app/

ENTRYPOINT ["/tmp/docker/entrypoint.sh"]

#开放端口
EXPOSE 80 8080