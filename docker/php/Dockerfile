FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev && \
    docker-php-ext-install gd bcmath

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV LD_LIBRARY_PATH /usr/local/instantclient/
ENV TNS_ADMIN /usr/local/instantclient/

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer
# oracle client
COPY ./instantclient-basic-linux.x64-12.2.0.1.0.zip /tmp
COPY ./instantclient-sdk-linux.x64-12.2.0.1.0.zip /tmp

# 必要なパッケージとPHPのssh2拡張機能のインストール
RUN apt-get update  \
    && apt-get -y install \
    git \
    zip \
    unzip \
    vim \
    libaio1 \
    wget \
    libonig-dev \
    libsqlite3-dev \ 
    && docker-php-ext-install pdo_mysql bcmath pdo_sqlite\
    && a2enmod rewrite \
    && pecl install xdebug-3.1.6  \
    && docker-php-ext-enable xdebug

# oracle client
RUN unzip /tmp/instantclient-basic-linux.x64-12.2.0.1.0.zip -d /usr/local/ && \
    unzip /tmp/instantclient-sdk-linux.x64-12.2.0.1.0.zip -d /usr/local/ && \
    ln -s /usr/local/instantclient_12_2 /usr/local/instantclient && \
    ln -s /usr/local/instantclient/libclntsh.so.12.1 /usr/local/instantclient/libclntsh.so && \
    ln -s /usr/local/instantclient/libnnz12.so /usr/local/instantclient/libnnz.so && \
    docker-php-ext-configure oci8 --with-oci8=instantclient,/usr/local/instantclient && \
    docker-php-ext-install oci8 && \ 
    docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/usr/local/instantclient,12.2 && \
    docker-php-ext-install pdo_oci && \
    rm -rf /usr/local/*.zip

# 必要なライブラリと拡張機能のインストール ssh
RUN apt-get install -y libssh2-1 libssh2-1-dev || true && yes '' | pecl install ssh2-1.2 && docker-php-ext-enable ssh2 || true
# ldap
RUN apt-get update && \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

RUN pecl upgrade-all

WORKDIR /var/www/html

RUN composer self-update

# パーミッションエラーを防ぐ
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

