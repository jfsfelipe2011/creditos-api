FROM php:7.1-apache
MAINTAINER jfsfelipe2011@gmail.com

COPY . /var/www/html/

RUN apt-get update && apt-get install -y vim \
    && a2enmod rewrite \
    && a2enmod headers \
    && a2enmod expires

RUN docker-php-source extract \
    # do important things \
    && docker-php-source delete \
    && docker-php-ext-install pdo_mysql \
                              mbstring

RUN mv /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/default.conf

COPY ./config/000-default.conf /etc/apache2/sites-available/