FROM mileschou/phalcon:7.2-fpm-alpine

RUN apk update && apk add autoconf php7-dev build-base
RUN pecl install mongodb
RUN apk del autoconf php7-dev build-base
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini