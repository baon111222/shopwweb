FROM php:8.2-cli

WORKDIR /app

COPY . .

# chạy server PHP luôn
CMD php -S 0.0.0.0:80
