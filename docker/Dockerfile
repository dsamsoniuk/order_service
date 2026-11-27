FROM php:8.4-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y libicu-dev libzip-dev \
    libpng-dev libfreetype6-dev libjpeg62-turbo-dev
RUN apt-get install -y zip
RUN apt-get install -y ffmpeg

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN docker-php-ext-configure intl 
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql intl zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

