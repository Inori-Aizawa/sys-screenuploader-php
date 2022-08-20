FROM php:7.4-apache as base

COPY ./ /var/www/html 
RUN touch /var/www/html/log.log
RUN chmod 777 /var/www/html/log.log
