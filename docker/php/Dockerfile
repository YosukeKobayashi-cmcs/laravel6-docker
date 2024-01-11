FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER 1

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
    && a2enmod rewrite \
    && pecl install xdebug-3.1.6  \
    && docker-php-ext-enable xdebug

# 必要なライブラリと拡張機能のインストール ssh
RUN apt-get install -y libssh2-1 libssh2-1-dev || true && yes '' | pecl install ssh2-1.2 && docker-php-ext-enable ssh2 || true

RUN pecl upgrade-all

WORKDIR /var/www/html

RUN composer self-update

# www-data USERでパーミッションエラーを防ぐ
RUN mkdir -p /var/www/html/public/coverage/tmp && \
    mkdir -p /var/www/html/public/coverage/reports && \
    mkdir -p /var/www/html/app/Coverage && \
    mkdir -p /var/www/html/storage && \
    mkdir -p /var/www/html/bootstrap && \
    mkdir -p /var/www/html/bootstrap/cache && \
    mkdir -p /var/www/.vscode-server

# コンテナが起動した際に実行されるパーミッションエラーを防ぐスクリプトをコピー
COPY ./set_permissions.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/set_permissions.sh
RUN sh /usr/local/bin/set_permissions.sh

# コンテナ起動時に権限設定スクリプトを実行
CMD ["set_permissions.sh"]

