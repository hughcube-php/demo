
ARG BASE_IMAGE=crpi-vrt7o5qsysdzjsbh.cn-shanghai.personal.cr.aliyuncs.com/zycube/demo:base-latest

# 代码构建
FROM ${BASE_IMAGE}-composer AS builder

# composer 慧哲私有仓库的账号密码
ARG COMPOSER_HZCUBE_USERNAME
ARG COMPOSER_HZCUBE_PASSWORD

# OCTANE
ARG OCTANE_GARBAGE=50
ARG OCTANE_MAX_WORKERS=2
ARG OCTANE_MAX_REQUESTS=500
ARG OCTANE_MAX_TASK_WORKERS=1
ARG OCTANE_MAX_EXECUTION_TIME=30

COPY . .

# 创建 preload.php
RUN echo "<?php " > preload.php

# 清除框架缓存
RUN rm -rf /app/bootstrap/cache/*
RUN find /app -name '.git' | xargs rm -rf

# 使用慧哲镜像
#RUN composer config --global repositories.hzcube composer https://packagist.x4k.net/composer
#RUN composer config --global --auth http-basic.packagist.x4k.net ${COMPOSER_HZCUBE_USERNAME} ${COMPOSER_HZCUBE_PASSWORD}

# composer安装依赖
RUN composer install --prefer-dist --optimize-autoloader --no-dev --profile
RUN composer dump-autoload --optimize --classmap-authoritative


# (一定要在修改代码之后)优化框架(\Illuminate\Foundation\Console\OptimizeClearCommand)
RUN php artisan config:clear && php artisan config:cache
RUN php artisan event:clear && php artisan event:cache
RUN php artisan view:clear && php artisan view:cache
RUN php artisan route:clear && php artisan route:cache

RUN php artisan opcache:create-preload
RUN php preload.php


# 创建数据表
RUN rm -rf "${APP_BASE_PATH}/database/database.sqlite"
RUN touch "${APP_BASE_PATH}/database/database.sqlite"
RUN php artisan migrate --force

# octane预处理
RUN php artisan octane:prepare \
        --host="0.0.0.0" \
        --port=80 \
        --workers="${OCTANE_MAX_WORKERS}" \
        --task-workers="${OCTANE_MAX_TASK_WORKERS}" \
        --max-requests="${OCTANE_MAX_REQUESTS}" \
        --state-file="$OCTANE_STATE_FILE"

#########################################################################################################
#### 运行环境
#########################################################################################################
FROM ${BASE_IMAGE}

COPY --from=builder --chown=www-data:www-data ${APP_BASE_PATH} ${APP_BASE_PATH}

RUN chown -R www-data:www-data /tmp/opcache/ && su -s /bin/sh - www-data -c "php /data/app/artisan opcache:compile-files"
