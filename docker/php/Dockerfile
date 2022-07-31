FROM php:8.1-apache
RUN apt update
RUN apt install git -y
#RUN apt install nodejs -y  Instala una versión anterior a la requerida por Yarn. No tengo tiempo para mirarlo
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer