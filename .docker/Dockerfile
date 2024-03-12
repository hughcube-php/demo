ARG BASE_IMAGE=php:8.3-apache


#########################################################################################################
#### 实际基础镜像
#########################################################################################################
FROM ${BASE_IMAGE}

ENV TZ=Asia/Shanghai

ENV APACHE_RUN_USER         www-data
ENV APACHE_RUN_GROUP        www-data

ENV APP_DIR                 /data/app
ENV APP_WEB_DIR             ${APP_DIR}/public

USER root:root
WORKDIR ${APP_DIR}

RUN set -eux; \
    apt-get update; \
    #
    # Enable apache modules
    a2enmod rewrite headers; \
    #
    # Disable apache modules
    rm -rf ${APACHE_CONFDIR}/mods-enabled/alias.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/alias.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/autoindex.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/autoindex.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/deflate.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/deflate.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/auth_basic.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/auth_basic.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/authn_core.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/authn_core.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/authn_file.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/authn_file.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/authz_host.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/authz_host.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/authz_user.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/authz_user.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/env.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/env.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/mime.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/mime.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/negotiation.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/negotiation.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/status.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/status.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/access_compat.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/access_compat.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/reqtimeout.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/reqtimeout.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/filter.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/filter.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/setenvif.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/setenvif.load; \
    rm -rf ${APACHE_CONFDIR}/mods-enabled/headers.conf && rm -rf ${APACHE_CONFDIR}/mods-enabled/headers.load; \
    rm -rf ${APACHE_CONFDIR}/conf-enabled/other-vhosts-access-log.conf; \
    #
    # 清除监听的端口
    echo > /etc/apache2/ports.conf; \
    #
    # Install BCMath ext
    docker-php-ext-install bcmath; \
    #
    # Install pcntl ext
    docker-php-ext-install pcntl; \
    #
    # Install pdo_mysql ext
    docker-php-ext-install pdo_mysql; \
    #
    # Install xhprof ext
    pecl install xhprof && docker-php-ext-enable xhprof; \
    #
    # Install GMP ext
    apt-get install -y libgmp-dev && docker-php-ext-install gmp; \
    #
    # Install APCU ext
    pecl install apcu && docker-php-ext-enable apcu; \
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
        echo "opcache.preload=/data/app/preload.php"; \
        echo "opcache.blacklist_filename=/usr/local/etc/php/opcache.blacklist"; \
        echo "opcache.error_log=/data/app/storage/logs/php-opcache-error.log"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini; \
    rm -rf /usr/local/etc/php/opcache.blacklist && touch /usr/local/etc/php/opcache.blacklist; \
    mkdir -p "${APP_DIR}" && echo "<?php " > "${APP_DIR}/preload.php"; \
    chown -R www-data:www-data "${APP_DIR}"; \
    #
    # Log Dir
    mkdir -p /data/app/storage/logs; \
    chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} /data/app/storage/logs; \
    #
    # phpinfo
    mkdir -p "${APP_WEB_DIR}" && echo "<?php phpinfo();" > "${APP_WEB_DIR}/index.php"; \
    chown -R www-data:www-data "${APP_WEB_DIR}"; \
    #
    # clean
    docker-php-source delete; \
    apt-get autoremove; \
    apt-get clean

COPY apache/sites-enabled/000-default.conf ${APACHE_CONFDIR}/sites-enabled/000-default.conf
COPY php/php.ini-production /usr/local/etc/php/php.ini

EXPOSE 9000
USER www-data:www-data
