FROM php:5.6-cli

RUN mkdir /opt/api-php-lib
RUN docker-php-ext-install pcntl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
