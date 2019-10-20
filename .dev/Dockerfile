FROM php:7.3-alpine
LABEL maintainer="Igor Lazarev <strider2038@yandex.ru>"

ARG ROADRUNNER_VERSION=1.5.0

ENV APP_ENV=dev \
    COMPOSER_HOME=/var/run/composer \
    XDEBUG_CONFIG="remote_enable=1 remote_autostart=1 remote_mode=req remote_port=9000 remote_host=172.17.0.1" \
    PHP_IDE_CONFIG="serverName=default"

RUN set -xe \
    && wget -O /tmp/rr.tar.gz "https://github.com/spiral/roadrunner/releases/download/v$ROADRUNNER_VERSION/roadrunner-$ROADRUNNER_VERSION-linux-amd64.tar.gz" \
    && tar -xzvf /tmp/rr.tar.gz -C /tmp \
    && rm -rf /tmp/rr.tar.gz \
    && cp "/tmp/roadrunner-$ROADRUNNER_VERSION-linux-amd64/rr" /usr/local/bin/rr \
    && rm -rf "/tmp/roadrunner-$ROADRUNNER_VERSION-linux-amd" \
    && apk add --update \
        $PHPIZE_DEPS \
        icu-dev \
        nano \
        iputils \
        bash \
        curl \
    && docker-php-ext-install \
        sockets \
        opcache \
    && pecl install xdebug \
    && docker-php-ext-enable \
        sockets \
        opcache \
        xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir -p $COMPOSER_HOME \
    && chmod -R 0777 $COMPOSER_HOME

COPY ./files /

EXPOSE 6001 8080 9000

CMD ["/usr/local/bin/rr", "serve", "-d", "-c", "/app/road-runner.dist.yaml"]

WORKDIR /app
