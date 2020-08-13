FROM php:7.4-fpm-alpine

ENV XDEBUG_VERSION=2.9.4

# Install packages we want to keep
RUN apk update && apk --no-cache add \
      libxml2-dev \
      libbz2 \
      zlib-dev \
      git \
      openssh

# Install dependencies that are only used during installation of other packages.
# Afterwards it will be cleaned up.
RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
      $PHPIZE_DEPS \
      coreutils \
      bzip2-dev \
    && docker-php-ext-install -j"$(/usr/bin/nproc)" opcache bcmath bz2 mysqli pdo pdo_mysql \
    && pecl install xdebug-$XDEBUG_VERSION \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY . /var/www

RUN cat /var/www/scripts/docker/php/20-xdebug.ini >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini

COPY ./scripts/docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN mkdir -p /root/.ssh

RUN ssh-keyscan github.com > /root/.ssh/known_hosts

ADD ./scripts/docker/php/id_rsa /root/.ssh/id_rsa
ADD ./scripts/docker/php/id_rsa.pub /root/.ssh/id_rsa.pub

RUN chmod 600 /root/.ssh/*

# Add nginx user for unix socket permission
RUN addgroup -S nginx \
  && adduser -S -D -G nginx nginx

WORKDIR /var/www

CMD ["php-fpm"]