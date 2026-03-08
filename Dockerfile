FROM php:8.1-apache

# Копируем все файлы сайта
COPY . /var/www/html/

# Включаем mod_rewrite для ЧПУ (если нужно)
RUN a2enmod rewrite

# Указываем рабочую директорию
WORKDIR /var/www/html/

# Даем права
RUN chown -R www-data:www-data /var/www/html/
