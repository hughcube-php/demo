ARG BASE_IMAGE=php:8.4.4-cli-alpine3.20


#########################################################################################################
#### 时区
#########################################################################################################
FROM ${BASE_IMAGE} AS tz-builder

RUN apk update && apk add tzdata


#########################################################################################################
#### 运行基础镜像
#########################################################################################################
FROM ${BASE_IMAGE}

ARG SWOOLE_VERSION="v6.0.1"
ARG SWOOLE_HTTP_SERVER_NAME="hz"

ENV APP_BASE_PATH                   /data/app
ENV APP_STORAGE_PATH                ${APP_BASE_PATH}/storage

ENV LOG_PATH                        /data/logs

ENV LARAVEL_OCTANE                  1
ENV OCTANE_STATE_FILE               ${APP_STORAGE_PATH}/logs/octane-server-state.json

COPY --from=tz-builder /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

RUN set -eux; \
    #
    # Update
    apk update; \
    #
    # Install build dependencies
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && docker-php-source extract; \
    #
    # Install Pcntl
    docker-php-ext-install pcntl; \
    #
    # Install BCMath
    docker-php-ext-install bcmath; \
    #
    # Install PDO_MYSQL
    docker-php-ext-install pdo_mysql; \
    #
    # Install xhprof ext
    pecl install xhprof && docker-php-ext-enable xhprof; \
    #
    # Install APCU ext
    pecl install apcu && docker-php-ext-enable apcu; \
    #
    # Install GMP
    apk add --no-cache gmp-dev && docker-php-ext-install gmp; \
    #
    # Install Intl
    apk add --no-cache icu-dev && docker-php-ext-install intl; \
    #
    # Install Zend OpCache \
    docker-php-ext-enable opcache;  \
    rm -rf /tmp/opcache && mkdir -p /tmp/opcache && chmod -R 0777 /tmp/opcache; \
    rm -rf /usr/local/etc/php/conf.d/*opcache.ini && touch /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini; \
    { \
        echo "zend_extension=opcache"; \
        echo "opcache.enable=1"; \
        echo "opcache.enable_cli=1"; \
        #echo "opcache.jit=1205"; \
        #echo "opcache.jit_buffer_size=256M"; \
        echo "opcache.memory_consumption=512"; \
        echo "opcache.interned_strings_buffer=64";  \
        echo "opcache.max_accelerated_files=1000000"; \
        echo "opcache.validate_timestamps=0";  \
        echo "opcache.revalidate_freq=86400"; \
        echo "opcache.revalidate_path=0"; \
        echo "opcache.save_comments=0"; \
        echo "opcache.enable_file_override=0"; \
        echo "opcache.max_file_size=0"; \
        echo "opcache.file_update_protection=0"; \
        echo "opcache.use_cwd=0"; \
        echo "opcache.file_cache=/tmp/opcache"; \
        echo "opcache.file_cache_consistency_checks=0"; \
        echo "opcache.validate_permission=0"; \
        echo "opcache.huge_code_pages=1"; \
        echo "opcache.preload=${APP_BASE_PATH}/preload.php"; \
        echo "opcache.error_log=${LOG_PATH}/php-opcache-error.log"; \
        echo "opcache.blacklist_filename=/usr/local/etc/php/opcache.blacklist"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini; \
    mkdir -p "${APP_BASE_PATH}" "${LOG_PATH}" && echo "<?php " > "${APP_BASE_PATH}/preload.php"; \
    rm -rf /usr/local/etc/php/opcache.blacklist && touch /usr/local/etc/php/opcache.blacklist; \
    #
    # Install Swoole \
    apk add --no-cache libstdc++ libpq; \
    apk add --no-cache --virtual .build-deps-ext-swoole curl-dev linux-headers openssl-dev pcre-dev pcre2-dev zlib-dev; \
    docker-php-ext-install sockets; \
    mkdir -p /usr/src/php/ext/swoole; \
    curl -sfL https://github.com/swoole/swoole-src/archive/${SWOOLE_VERSION}.tar.gz -o swoole.tar.gz; \
    tar xfz swoole.tar.gz --strip-components=1 -C /usr/src/php/ext/swoole; \
    if [ -n "${SWOOLE_HTTP_SERVER_NAME}" ]; then \
        sed -i "s/#define SW_HTTP_SERVER_SOFTWARE \"swoole-http-server\"/#define SW_HTTP_SERVER_SOFTWARE \"${SWOOLE_HTTP_SERVER_NAME}\"/"  "/usr/src/php/ext/swoole/include/swoole_config.h"; \
    fi; \
    docker-php-ext-configure swoole; \
    docker-php-ext-install -j$(nproc) swoole; \
    rm -f swoole.tar.gz && apk del --no-network .build-deps-ext-swoole;  \
    #
    # Clear \
    docker-php-source delete && pecl clear-cache && apk del --no-network .build-deps; \
    rm -rf "/tmp/opcache/*" "${LOG_PATH}/*"; \
    #
    # test
    php -m

WORKDIR ${APP_BASE_PATH}

CMD /usr/local/bin/php \
        -d expose_php="Off" \
        -d variables_order="EGPCS" \
        -d realpath_cache_size="16M" \
        -d realpath_cache_ttl="86400" \
        -d error_reporting="E_ALL" \
        -d error_log="${LOG_PATH}/php-error.log" \
        "${APP_BASE_PATH}/vendor/bin/swoole-server" "${OCTANE_STATE_FILE}"
