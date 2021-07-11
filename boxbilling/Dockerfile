# FROM php:7.4.0-fpm-alpine
FROM php:7.4.16-fpm-alpine
LABEL maintainer="Nofrets Poai"

# Then we define some variables which will need later on:
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1 \
    PHPREDIS_VERSION=5.1.1 
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Let’s install some building dependencies:
RUN apk --no-cache add \
    icu-dev \
    gettext \
    gettext-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-configure gettext \
    && docker-php-ext-install \
    intl \
    gettext

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \            
                tzdata \
            $PHPIZE_DEPS \
            # And some packages which we need to remain. Don’t combine this - as we will remove the apk set `.build-deps later on.
            && apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted \
            && cp /usr/share/zoneinfo/UTC /etc/localtime \
            && echo 'UTC' > /etc/localtime \
            && apk add --no-cache \
                    openssl-dev \
                    bash \
                    freetype-dev \
                    libpng-dev \
                    libjpeg-turbo-dev \
                    sqlite-dev \
                    curl \
                    curl-dev \
                    libsodium-dev \
                    icu-dev \
                    libxml2-dev \
                    recode-dev \
                    libxslt-dev \
                    git \
                    postgresql-client \
                    postgresql-dev \
                    openssh-client \
                    libmcrypt-dev \
                    libmcrypt \
                    libzip-dev \
                    libgcrypt-dev \
                    oniguruma-dev \
            && apk --update --no-cache add grep \
            && mkdir -p /usr/src/php/ext/redis \
            && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
            && echo 'redis' >> /usr/src/php-available-exts \
            # Time to compile some extensions - we don’t need them all the time - but it doesn’t hurt to have them.
            && docker-php-ext-configure gd \
                --with-freetype=/usr/include/ \
                --with-jpeg \
            && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)\
                gd \
                bcmath \
                opcache \
                iconv \
                mysqli \
                pdo \
                pdo_mysql \
                pdo_sqlite \
                pdo_pgsql \
                zip \
                # zlib \
                # gettext \
                xml \
                xsl \
                intl \
                json \
                mbstring \
                curl \
                simplexml \
                soap \
                redis \
            && docker-php-ext-install sodium \
            # && docker-php-ext-install gettext \
            # As mentioned earlier, time to clean up, so we have a nice small container.
            && apk del .build-deps \
            && rm -rf /tmp/* /var/cache/apk/* 
            # Now it’s time to set some ini parameters we want to use:
RUN echo "memory_limit = 128M" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time = 60" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "expose_php = Off" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "pm.status_path = /status" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "ping.path = /ping" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# As we need composer anyway, we install it right in the container:
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer global require hirak/prestissimo --no-plugins --no-scripts
RUN addgroup -S sudo \
    && adduser -h /home/web -u 1000 -S -s /bin/bash -G sudo web \
    && echo "%sudo ALL=NOPASSWD:ALL" > /etc/sudoers

