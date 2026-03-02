
ARG BASE_IMAGE=crpi-vrt7o5qsysdzjsbh.cn-shanghai.personal.cr.aliyuncs.com/zycube/demo:base-latest

# 代码构建
FROM ${BASE_IMAGE}-composer AS builder

# composer 慧哲私有仓库的账号密码
ARG COMPOSER_HZCUBE_USERNAME
ARG COMPOSER_HZCUBE_PASSWORD

# 标记当前处于构建阶段，跳过 Telescope 数据记录等运行时行为
ARG APP_BUILDING=1

# 是否排除 dev 依赖（默认排除，dev 环境传 --build-arg COMPOSER_NO_DEV=0 以安装 Telescope 等开发包）
ARG COMPOSER_NO_DEV=1

# 标记 Octane 运行模式，让 artisan 命令感知 Swoole 环境（如 config:cache 写入 Octane 相关配置）
ARG LARAVEL_OCTANE=1

# OCTANE
ARG OCTANE_GARBAGE=50
ARG OCTANE_MAX_WORKERS=2
ARG OCTANE_MAX_REQUESTS=500
ARG OCTANE_MAX_TASK_WORKERS=1
ARG OCTANE_MAX_EXECUTION_TIME=30
ARG OCTANE_REACTOR_NUM=1

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
RUN composer install --prefer-dist --optimize-autoloader $([ "${COMPOSER_NO_DEV}" = "1" ] && echo "--no-dev") --profile
RUN composer dump-autoload --optimize --classmap-authoritative

# (一定要在修改代码之后)优化框架(\Illuminate\Foundation\Console\OptimizeClearCommand)
RUN php artisan config:clear && php artisan config:cache
RUN php artisan event:clear && php artisan event:cache
RUN php artisan view:clear && php artisan view:cache
RUN php artisan route:clear && php artisan route:cache

# 先生成 preload
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

# 是否跳过 OPcache 预编译（dev 构建文件数过多可能导致 OOM，可传 --build-arg SKIP_OPCACHE_COMPILE=1 跳过）
ARG SKIP_OPCACHE_COMPILE=0

# 标记构建阶段（见 builder 阶段注释）
ARG APP_BUILDING=1

# 标记 Octane 运行模式（见 builder 阶段注释）
ARG LARAVEL_OCTANE=1

COPY --from=builder  ${APP_BASE_PATH} ${APP_BASE_PATH}
RUN if [ "${SKIP_OPCACHE_COMPILE}" = "0" ]; then php /data/app/artisan opcache:compile-files; fi
