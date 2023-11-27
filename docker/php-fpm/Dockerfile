FROM php:8.0-fpm

ARG UNAME=appuser
ARG UID=1000
ARG GID=1000
ARG XDEBUG_VERSION=3.1.6

RUN apt-get update && apt-get install -y \
    curl \
    g++ \
    git \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libonig-dev\
    libmcrypt-dev \
    libpng-dev \
    libzip-dev \
    libreadline-dev \
    libxml2-dev \
    vim \
    sudo \
    unzip \
    zip \
    percona-toolkit \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    bcmath \
    bz2 \
    calendar \
    iconv \
    intl \
    mbstring \
    opcache \
    pdo_mysql \
    xml \
    zip

RUN pecl install xdebug-${XDEBUG_VERSION} \
    && { \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)"; \
    echo "xdebug.mode=debug"; \
    echo "xdebug.start_with_request=yes"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.client_port=9003"; \
} > /usr/local/etc/php/conf.d/xdebug.ini;

RUN wget https://getcomposer.org/installer -O - -q \
    | php -- --install-dir=/bin --filename=composer --quiet

RUN groupadd -g $GID -o $UNAME
RUN useradd -m -u $UID -g $GID -o -s /bin/bash $UNAME

USER $UNAME
WORKDIR /var/www

EXPOSE 80/tcp