FROM php:8.1-cli-alpine3.16

# Install important deps
RUN apk update && \
    apk upgrade && \
    apk add $PHPIZE_DEPS git zip curl openssl openssh

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php &&  \
    php -r "unlink('composer-setup.php');" && \
    mv ./composer.phar /usr/local/bin/composer

RUN docker-php-ext-enable opcache

# Install dependencies for pecl packages
RUN apk add rabbitmq-c-dev libuuid libpq

# Install php extensions which are only available on pecl
RUN yes '' | pecl install  \
    uuid \
    rabbitmq-c-dev

# Install php extensions which are available with docker-php-ext-install
RUN docker-php-ext-install \
    pgsql \
    pdo_pgsql \
    xml \
    simplexml \
    mbstring
