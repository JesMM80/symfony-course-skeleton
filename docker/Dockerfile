FROM php:8.3-fpm-bullseye

ARG UID

RUN adduser -u ${UID} --disabled-password --gecos "" appuser \
    && mkdir /home/appuser/.ssh \
    && chown -R appuser:appuser /home/appuser/ \
    && echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config \
    && echo "alias sf=/appdata/www/bin/console" >> /home/appuser/.bashrc

# Instalar dependencias del sistema
RUN apt-get update \
    && apt-get install -y git acl openssl openssh-client wget zip unzip gnupg \
    libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && docker-php-ext-install intl pdo zip pdo_mysql \
    && pecl install xdebug apcu \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug apcu

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Node.js 20 y Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && corepack enable \
    && corepack prepare yarn@stable --activate

# Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt-get install -y symfony-cli

# Copiar configuración xdebug si la tienes
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /appdata/www
