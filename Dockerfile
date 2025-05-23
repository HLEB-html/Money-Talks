FROM php:8.4-apache

# Устанавливаем необходимые расширения для PHP
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Включаем модуль Apache для работы с .htaccess
RUN a2enmod rewrite

# Копируем файл php.ini
COPY php.ini /usr/local/etc/php/

# Копируем исходный код в контейнер
COPY src/ /var/www/html/

# Настроим рабочую директорию
WORKDIR /var/www/html

# Открываем порт 80
EXPOSE 80
