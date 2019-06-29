FROM php:7.3-cli

RUN docker-php-ext-install pcntl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
