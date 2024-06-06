# Use a PHP base image
FROM php:8.2-apache

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

COPY ./src /var/www/html
WORKDIR /var/www/html/src

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000"]