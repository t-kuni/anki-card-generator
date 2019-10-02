FROM php:7.3.10-cli-alpine3.10

#
# アプリケーション実行環境の構築
#
RUN apk update \
    && apk add --no-cache nodejs=10.16.3-r0 npm=10.16.3-r0 chromium autoconf automake libtool gcc libc-dev libpng-dev lcms2-dev nasm make
# Add xdebug
RUN pecl install xdebug-2.7.2 \
    && docker-php-ext-enable xdebug
# Add Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"
# Add Extension
RUN docker-php-ext-install mbstring sockets \
    && docker-php-ext-enable mbstring sockets

RUN mkdir /app
WORKDIR /app

CMD ["php", "/app/entrypoint.php"]
