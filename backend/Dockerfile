FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libmariadb-dev-compat libmariadb-dev libzip-dev libicu-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

ARG USER=appuser
RUN adduser --disabled-password --gecos "" ${USER} \
    && chown -R ${USER}:${USER} /var/www

USER ${USER}
