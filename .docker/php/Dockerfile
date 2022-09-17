FROM php:8.0-fpm

RUN rm -rf /var/lib/apt/lists/* && \
    apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
    vim \
    zip \
    unzip \
    curl \
    git \
&& docker-php-source extract \
&& docker-php-ext-install sockets pdo pdo_pgsql intl zip xml \
&& apt-get clean

# apcuのインストール
RUN pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl clear-cache

EXPOSE 9000

WORKDIR /app
