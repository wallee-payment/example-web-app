FROM php:7.4.14-apache-buster

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY common.php /var/www/html/
COPY confirm.php /var/www/html/
COPY install.php /var/www/html/

COPY composer.json /var/www/html/

RUN apt update && apt install -y git unzip

RUN cd /var/www/html && composer update && composer install