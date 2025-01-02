FROM php:8.2.27-fpm

RUN apt-get update && \
    apt-get install -y

RUN apt-get install -y curl vim wget git curl libgdal-dev \
    build-essential libssl-dev zlib1g-dev libpng-dev \
    libjpeg-dev libfreetype6-dev libonig-dev libicu-dev \
    libzip-dev unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

COPY /docker/denteasy.conf /etc/apache2/sites-enabled/000-default.conf

RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring gd intl zip && docker-php-ext-enable mysqli

RUN docker-php-ext-configure intl

RUN chown -R www-data:www-data /var/www/html && a2enmod rewrite && a2enmod headers proxy_http

RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini

RUN echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini

RUN service apache2 restart

WORKDIR /var/www/html

USER www-data

EXPOSE 80
EXPOSE 443