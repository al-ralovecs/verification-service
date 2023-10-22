ARG BUILD_ROLE
ARG PHP_VERSION=8.2
ARG ALPINE_VERSION=3.16

##########################################################################
# Base CLI role
##########################################################################

FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION} as base-image

ENV TZ="Europe/Riga"
ENV APP_USER_NAME="php-data"
ENV APP_USER_GROUP="php-data"
ENV APP_DIR="/var/www/app"

RUN addgroup -S "$APP_USER_GROUP" && adduser -S "$APP_USER_NAME" -G "$APP_USER_GROUP" -s /bin/sh

# Global dependencies
RUN apk add --update git gettext shadow su-exec supervisor tzdata openssh-client libpq-dev && rm -rf /tmp/* /var/cache/apk/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# PHP Extensions
RUN install-php-extensions @composer zip amqp pcntl

##########################################################################
# Common part for each role, which depends on code
##########################################################################

###> code-aware-base ###
FROM base-image as code-aware-base

COPY --chown=${APP_USER_NAME}:${APP_USER_GROUP} . ${APP_DIR}

RUN mkdir -p ${APP_DIR}/var/cache ${APP_DIR}/var/log
RUN chown -R ${APP_USER_NAME}:${APP_USER_GROUP} ${APP_DIR}/var
RUN chmod -R 0777 ${APP_DIR}/var/log
RUN chmod -R 0755 ${APP_DIR}/var/cache
###< code-aware-base ###

##########################################################################
# Code aware container with vendors installed
##########################################################################

###> vendors-installed ###
ARG GITLAB_ACCESS_TOKEN

FROM code-aware-base as vendors-installed

WORKDIR ${APP_DIR}

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer config -g gitlab-token.gitlab.accrela.io ${GITLAB_ACCESS_TOKEN}
RUN composer install
RUN composer dump-autoload
###< vendors-installed ###

##########################################################################
# Base CLI roles
##########################################################################

FROM base-image             as dev-base
FROM vendors-installed      as ci-base
FROM vendors-installed      as consumer-base

##########################################################################
# Common part for each role
##########################################################################

###> base (Common dependencies) ###
FROM ${BUILD_ROLE}-base as base
###< base ###

##########################################################################
# CI
##########################################################################

FROM base as ci

# PHP Production Configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . ${APP_DIR}

WORKDIR ${APP_DIR}

COPY etc/entrypoint/ci.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

##########################################################################
# CLI ROLES
##########################################################################

###> cli/consumer (Message consumer role) ###
FROM base as consumer

# PHP Production Configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . ${APP_DIR}

WORKDIR ${APP_DIR}

# Supervisor
ADD etc/supervisor/supervisord.conf /etc/
ADD etc/supervisor/consumer.conf /etc/supervisor/conf.d/consumer.conf.template
ADD etc/supervisor/consumer-failed.conf /etc/supervisor/conf.d/consumer-failed.conf.template
ADD etc/supervisor/consumer-manager.conf /etc/supervisor/conf.d/consumer-manager.conf.template

ENV APP_CONSUMER_COUNT=1
ENV APP_CONSUMER_MEMORY_LIMIT="128M"
ENV APP_CONSUMER_TIME_LIMIT=3600
ENV APP_CONSUMER_MANAGER_COUNT=1
ENV APP_CONSUMERS_PER_MANAGER=10
ENV APP_CONSUMER_MANAGER_MEMORY_LIMIT=128
ENV APP_CONSUMER_MANAGER_TIME_LIMIT=3600

# Entrypoint
COPY etc/entrypoint/consumer.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

CMD ["supervisord" , "--nodaemon", "--configuration", "/etc/supervisord.conf"]
###< cli/consumer ###

##########################################################################
# Development
##########################################################################

FROM base as dev

# PHP Configs
COPY etc/conf/php-fpm.conf /usr/local/etc/

# PHP Development Configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# NGinx
RUN apk update && apk add nginx && rm -rf /tmp/* /var/cache/apk/*
COPY etc/nginx/sites/app.verification.conf /etc/nginx/sites-enabled/app.verification.conf
COPY etc/nginx/sites/app.template.conf /etc/nginx/sites-enabled/app.template.conf
COPY etc/nginx/snippets /etc/nginx/snippets
COPY etc/nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

# Supervisor
RUN apk add --update supervisor && rm -rf /tmp/* /var/cache/apk/*
ADD etc/supervisor/supervisord.conf /etc/
ADD etc/supervisor/php-fpm.conf /etc/supervisor/conf.d/
ADD etc/supervisor/nginx.conf /etc/supervisor/conf.d/

COPY etc/entrypoint/web.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

CMD ["supervisord" , "--nodaemon", "--configuration", "/etc/supervisord.conf"]

##########################################################################
# Container build
##########################################################################

FROM $BUILD_ROLE

ARG BUILD_ROLE

ENV APP_ROLE=$BUILD_ROLE

# Ensure entrypoint is executable
RUN if test -f "/entrypoint.sh"; then chmod +x /entrypoint.sh ; fi

WORKDIR ${APP_DIR}