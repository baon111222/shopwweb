FROM php:8.2-apache

# TẮT mpm_event (gây lỗi)
RUN a2dismod mpm_event

# BẬT mpm_prefork (đúng cho PHP)
RUN a2enmod mpm_prefork

# bật rewrite
RUN a2enmod rewrite

# copy code
COPY . /var/www/html/

# set quyền
RUN chmod -R 755 /var/www/html

EXPOSE 80
