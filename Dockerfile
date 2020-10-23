FROM php:5.4
RUN docker-php-ext-install mysql

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www"]
