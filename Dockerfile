FROM php:8.2-apache

# TẮT hết MPM có thể gây lỗi
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true

# BẬT đúng cái PHP cần
RUN a2enmod mpm_prefork

# bật rewrite
RUN a2enmod rewrite

# copy code
COPY . /var/www/html/

# quyền
RUN chmod -R 755 /var/www/html

EXPOSE 80
