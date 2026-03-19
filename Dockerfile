FROM php:8.2-apache

# bật rewrite (tránh lỗi web)
RUN a2enmod rewrite

# copy toàn bộ code vào web root
COPY . /var/www/html/

# set quyền (rất quan trọng)
RUN chmod -R 755 /var/www/html

# expose port
EXPOSE 80
