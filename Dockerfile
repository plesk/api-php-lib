FROM php:7.2-cli

RUN mkdir /opt/api-php-lib
RUN docker-php-ext-install pcntl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
