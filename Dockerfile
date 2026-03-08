FROM php:8.1-apache

# Обновляем пакеты и устанавливаем необходимые расширения
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

# Копируем все файлы сайта
COPY . /var/www/html/

# Включаем mod_rewrite
RUN a2enmod rewrite

# Даем права на файлы
RUN chown -R www-data:www-data /var/www/html/

# Указываем рабочую директорию
WORKDIR /var/www/html/
