FROM phalconphp/php-fpm:7.2-min

RUN apt-get update \
 && apt-get install -y git zlib1g-dev \
 && docker-php-ext-install pdo

FROM conf
COPY nginx/default.conf /etc/nginx/conf.d/nginx.conf