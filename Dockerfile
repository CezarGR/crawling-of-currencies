FROM php:8.1-apache

# Instalando o composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --version=2.0.9 && \
    rm composer-setup.php && \
    chmod +x composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN	apt-get update

RUN apt install nodejs npm zip unzip libzip-dev curl -y && \
    npm install -g n && \
    n stable

# Instalando extensões do php
RUN docker-php-ext-install pdo pdo_mysql zip && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    rm -rf /tmp/pear

## App configurations
COPY ./docker/php.ini /usr/local/etc/php/php.ini
WORKDIR /app

## Apache configuration
RUN a2enmod rewrite; \
    rm -rf /var/www/html && \
    ln -s /app/public /var/www/html