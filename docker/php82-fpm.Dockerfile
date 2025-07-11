FROM php:8.2-fpm

RUN apt-get update

RUN apt-get install -y curl git zip zlib1g-dev libzip-dev libjpeg62-turbo-dev libfreetype6-dev libwebp-dev libgd-dev  libpng-dev libmagickwand-dev \
    && rm -rf /var/lib/apt/list/* 

RUN pecl install imagick && \
    docker-php-ext-enable imagick

RUN docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-install zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-enable gd

RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/app
