FROM php:8.1-fpm-bullseye

ARG UID

RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "alias sf=/appdata/www/bin/console" >> /home/appuser/.bashrc

RUN apt-get update \
    && apt-get install -y git acl openssl openssh-client wget zip unzip gnupg \
    && apt-get install -y libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && docker-php-ext-install intl pdo zip pdo_mysql \
    && pecl install xdebug-3.1.6 apcu-5.1.21 \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug apcu

RUN curl -sS https://getcomposer.org/download/2.2.21/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get install -y symfony-cli

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /appdata/www
