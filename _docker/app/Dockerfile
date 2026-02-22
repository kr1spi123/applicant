FROM php:8.4-fpm

# Установка системных пакетов
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip unzip \
    git \
    curl \
    libxml2-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Установка расширений по одному
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install xml
RUN docker-php-ext-install sockets
# intl убрали - не обязательно для Laravel
# opcache уже включен в PHP 8.4 по умолчанию

# Установка php.ini
COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/app.ini

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www

# Копирование проекта
COPY . .

# Установка прав
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache