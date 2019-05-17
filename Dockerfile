FROM php:7.3

MAINTAINER wene<china_wangyu@aliyun.com>

# Version
ENV APP_PORT="8080"
ENV APP_HOST="0.0.0.0"
ENV APP_DIR="/app"

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone

# 设置国内源
RUN mv /etc/apt/sources.list /etc/apt/sources.list.back && \
     echo '# 默认注释了源码镜像以提高 apt update 速度，如有需要可自行取消注释 \n \
     deb http://mirrors.aliyun.com/debian/ stretch main non-free contrib \n \
     deb-src http://mirrors.aliyun.com/debian/ stretch main non-free contrib \n \
     deb http://mirrors.aliyun.com/debian-security stretch/updates main \n \
     deb-src http://mirrors.aliyun.com/debian-security stretch/updates main \n \
     deb http://mirrors.aliyun.com/debian/ stretch-updates main non-free contrib \n \
     deb-src http://mirrors.aliyun.com/debian/ stretch-updates main non-free contrib \n \
     deb http://mirrors.aliyun.com/debian/ stretch-backports main non-free contrib \n \
     deb-src http://mirrors.aliyun.com/debian/ stretch-backports main non-free contrib' >> /etc/apt/sources.list

# Libs
RUN apt-get update \
    && apt-get install -y \
        curl \
        wget \
        git \
        zip \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
    && apt-get clean \
    && apt-get autoremove

# PDO extension
RUN docker-php-ext-install pdo_mysql

# Bcmath extension
RUN docker-php-ext-install bcmath

# 设置工作目录
ADD . $APP_DIR
WORKDIR $APP_DIR
VOLUME $APP_DIR

# Composer
# 设置composer中国镜像
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.8.5

RUN curl --silent --fail --location --retry 3 --output /tmp/installer.php --url https://raw.githubusercontent.com/composer/getcomposer.org/cb19f2aa3aeaa2006c0cd69a7ef011eb31463067/web/installer \
 && php -r " \
    \$signature = '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5'; \
    \$hash = hash('sha384', file_get_contents('/tmp/installer.php')); \
    if (!hash_equals(\$signature, \$hash)) { \
      unlink('/tmp/installer.php'); \
      echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
      exit(1); \
    }" \
 && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
 && composer --ansi --version --no-interaction \
 && rm -f /tmp/installer.php

RUN composer install --no-dev \
    && composer dump-autoload -o \
    && composer clearcache

EXPOSE $APP_PORT

ENTRYPOINT ["php","think", "run","-H $APP_HOST","-p $APP_PORT"]