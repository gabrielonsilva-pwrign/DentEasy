FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y

RUN apt-get install -y curl vim wget git curl libgdal-dev build-essential libssl-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libicu-dev

COPY . /var/www/html

COPY /docker/denteasy.conf /etc/apache2/sites-enabled/000-default.conf

RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring gd intl

RUN docker-php-ext-configure intl

RUN mkdir /var/www/html/writable/cache

RUN mkdir /var/wwww/html/public/uploads

RUN chown -R www-data:www-data /var/www/html && a2enmod rewrite && a2enmod headers proxy_http

RUN service apache2 restart

WORKDIR /var/www/html

EXPOSE 80
EXPOSE 443