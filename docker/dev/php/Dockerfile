# https://github.com/mlocati/docker-php-extension-installer
FROM php:8.4-fpm

# Install system dependencies
RUN apt update && apt install -y \
    libicu-dev \
    libpq-dev \
    libyaml-dev \
    librabbitmq-dev \
    libxtst6 \
    libzip-dev \
    p7zip \
    git \
    unzip \
    zip

# Install the extension installer
COPY --from=mlocati/php-extension-installer:2.7.17 /usr/bin/install-php-extensions /usr/local/bin/

# Install PHP extensions
# The installer handles system libraries (like libpng, libwebp, libc-client) automatically
RUN install-php-extensions \
    opcache \
    igbinary \
    redis \
    apcu \
    yaml \
    json_post \
    amqp \
    gd \
    imap \
    intl \
    pdo_pgsql \
    pcntl \
    xdebug \
    zip \
    pcov

# Dev
# composer
COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer
