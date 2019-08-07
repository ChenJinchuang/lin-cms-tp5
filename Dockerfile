ARG PHP_VERSION=7.2

FROM php:$PHP_VERSION

LABEL maintainer="嗝嗝 <china_wangyu@aliyun.com>"

# --build-arg timezone=Asia/Shanghai
ARG timezone

# app env: prod pre test dev
ARG app_env=prod
# default use www-data user
ARG work_user=www-date

ENV APP_ENV=${app_env:-"prod"} \
    TIMEZONE=${timezone:-"Asia/Shanghai"} \
    PHPREDIS_VERSION=4.3.0 \
    SWOOLE_VERSION=4.4.2 \
    COMPOSER_ALLOW_SUPERUSER=1

# Libs -y --no-install-recommends
RUN apt-get update \
    && apt-get install -y \
        curl wget git zip unzip less vim openssl \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
# Install composer
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \
# Install PHP extensions
    && docker-php-ext-install \
       bcmath gd pdo_mysql mbstring sockets zip sysvmsg sysvsem sysvshm \
# Install redis extension
   && wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
   && pecl install /tmp/redis.tar.tgz \
   && rm -rf /tmp/redis.tar.tgz \
   && docker-php-ext-enable redis \
# Install swoole extension
    && wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
        cd swoole \
        && phpize \
        && ./configure --enable-mysqlnd --enable-sockets --enable-openssl --enable-http2 \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole \
# Clear dev deps
    && apt-get clean \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
# Timezone
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && echo "[Date]\ndate.timezone=${TIMEZONE}" > /usr/local/etc/php/conf.d/timezone.ini



# Install composer deps
ADD . /var/www/lin-cms-tp5
RUN  cd /var/www/lin-cms-tp5 \
    && composer install \
    && composer clearcache

WORKDIR /var/www/lin-cms-tp5
EXPOSE 8000

CMD ["php", "/var/www/lin-cms-tp5/think", "run", "-H 0.0.0.0"]

