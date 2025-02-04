FROM php:8.2.27-apache

RUN apt-get update && apt-get install -y \
    curl vim wget git libgdal-dev \
    build-essential libssl-dev zlib1g-dev libpng-dev \
    libjpeg-dev libfreetype6-dev libonig-dev libicu-dev \
    libzip-dev unzip \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY src/. /var/www/html/denteasy

COPY docker/entrypoint.sh /usr/local/bin/

COPY docker/denteasy.conf /etc/apache2/sites-enabled/000-default.conf

RUN docker-php-ext-install intl opcache pdo_mysql mysqli pdo mbstring zip gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd \
    && docker-php-ext-enable mysqli gd intl opcache

RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source" > /usr/local/etc/php/conf.d/disable_functions.ini


RUN mkdir -p /var/www/.composer \
    /var/www/html/denteasy/writable/logs \
    /var/www/html/denteasy/writable/cache \
    /var/www/html/denteasy/writable/session \
    /var/www/html/denteasy/writable/debugbar \
    /var/www/html/denteasy/writable/backups \
    /var/www/html/denteasy/public/uploads \
    && chown -R www-data:www-data /var/www/html /var/www/.composer \
    && chmod -R 755 /var/www/html /var/www/.composer \
    && chmod +x /usr/local/bin/entrypoint.sh

RUN a2enmod rewrite headers proxy_http

WORKDIR /var/www/html

USER www-data

EXPOSE 80 443

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["apache2-foreground"]

