FROM php:8.1-apache
COPY . /var/www/html/
RUN chmod -R 755 /var/www/html/
EXPOSE 80
