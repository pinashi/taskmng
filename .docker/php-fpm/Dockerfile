FROM php:8.2-fpm
RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y libzip-dev zip unzip
RUN docker-php-ext-install zip