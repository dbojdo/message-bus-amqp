FROM php:7.0-cli

RUN apt-get update && apt-get install -y \
       libmcrypt-dev \
       libpq-dev \
       git \
       subversion \
   && docker-php-ext-install -j$(nproc) iconv mcrypt bcmath \
   && docker-php-ext-install -j$(nproc) zip \
   && pecl install apcu \
   && docker-php-ext-enable apcu \
   && pecl install xdebug \
   && docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html
CMD composer update -n
