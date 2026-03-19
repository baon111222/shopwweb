FROM php:8.2-apache

# bật rewrite (web PHP hay cần)
RUN a2enmod rewrite

# copy code
COPY . /var/www/html/

# set quyền (tránh crash)
RUN chown -R www-data:www-data /var/www/html

# expose port
EXPOSE 80
