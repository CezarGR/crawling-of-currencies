FROM php:8.1-apache

# Instalando o composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --version=2.0.9 && \
    rm composer-setup.php && \
    chmod +x composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN	apt-get update

RUN apt install nodejs npm -y

# Instalando extensões do php
RUN docker-php-ext-install pdo pdo_mysql && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    rm -rf /tmp/pear

## App configurations
COPY ./src /app
COPY ./docker/php.ini /usr/local/etc/php/php.ini
WORKDIR /app
RUN ["cp", ".env.example", ".env"]
RUN composer install --no-cache --optimize-autoloader --no-dev

## Apache configuration
RUN a2enmod rewrite; \
    chown -R www-data:www-data /app/storage; \
    rm -rf /var/www/html && \
    ln -s /app/public /var/www/html
