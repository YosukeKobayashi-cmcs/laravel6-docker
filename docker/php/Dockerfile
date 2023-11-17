FROM php:7.4-apache

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf   
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# 必要なパッケージとPHPのssh2拡張機能のインストール
RUN apt-get update  \
    && apt-get -y install \
    git \
    zip \
    unzip \
    vim \
    libonig-dev \
    && docker-php-ext-install pdo_mysql bcmath \
    && a2enmod rewrite

# Install ssh2 extension
RUN apt-get install -y libssh2-1 libssh2-1-dev

WORKDIR /var/www/html

EXPOSE 80