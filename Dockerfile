FROM php:8.2-apache

# fix MPM dứt điểm
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# rewrite
RUN a2enmod rewrite

COPY . /var/www/html/

RUN chmod -R 755 /var/www/html

EXPOSE 80
