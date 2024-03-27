FROM php:8.3.3-fpm-alpine3.18

COPY --from=mlocati/php-extension-installer:2.2.2 /usr/bin/install-php-extensions /usr/local/bin/

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions \
        opcache \
        intl \
        bcmath \
        mcrypt \
        gd \
        zip

WORKDIR /var/www/html/public