FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y libzip-dev zip && \
    docker-php-ext-install pdo_mysql

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite