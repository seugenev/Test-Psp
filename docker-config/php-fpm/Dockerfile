FROM php:8.3.0-fpm
#FROM php:8.2.3-fpm

ARG xdebug=1

# Workaround for java install error
# https://bugs.debian.org/cgi-bin/bugreport.cgi?bug=863199
RUN mkdir -p /usr/share/man/man1

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install selected extensions and other stuff

RUN apt-get update && apt-get install -y \
    apt-utils \
    libfreetype6-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    imagemagick \
    libmagickwand-dev \
    libmcrypt-dev \
    libbz2-dev \
    zlib1g \
    unzip \
    libyaml-dev \
    libxml2-dev \
    libicu-dev \
    libxslt-dev \
    git \
    default-jre \
    procps


RUN apt-get update && apt-get install -y libzip-dev zip supervisor && docker-php-ext-install zip

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd

RUN docker-php-ext-install -j$(nproc) pdo_mysql bcmath bz2 intl soap xsl opcache exif

RUN pecl install imagick && docker-php-ext-enable imagick
RUN pecl install redis && docker-php-ext-enable redis

# Install XDebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.discover_client_host = true" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.mode='debug'" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.log='/tmp/xdebug.log'" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.client_host = 127.0.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled \
    && echo "xdebug.client_port = 9009" >> /usr/local/etc/php/conf.d/xdebug.ini.disabled

RUN apt-get update && apt-get install -y jpegoptim --no-install-recommends && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y optipng --no-install-recommends && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y pngquant --no-install-recommends && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y gifsicle --no-install-recommends && rm -r /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y webp --no-install-recommends && rm -r /var/lib/apt/lists/*


RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR "/application"
RUN usermod -u 1000 www-data;
RUN chown -R www-data:www-data /application
