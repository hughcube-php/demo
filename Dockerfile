
ARG BASE_IMAGE=registry.cn-hangzhou.aliyuncs.com/hughcube/php-demo:base-latest

# 代码构建
FROM ${BASE_IMAGE}-composer AS builder

# composer 慧哲私有仓库的账号密码
ARG COMPOSER_HZCUBE_USERNAME
ARG COMPOSER_HZCUBE_PASSWORD

USER root:root
WORKDIR ${APP_DIR}

COPY . .

# 使用慧哲镜像
#RUN composer config --global repositories.hzcube composer https://packagist.x4k.net/composer
#RUN composer config --global --auth http-basic.packagist.x4k.net ${COMPOSER_HZCUBE_USERNAME} ${COMPOSER_HZCUBE_PASSWORD}

# composer安装依赖
RUN composer install --prefer-dist --optimize-autoloader --no-dev --profile
RUN composer dump-autoload --optimize --classmap-authoritative

# 优化项目(\Illuminate\Foundation\Console\OptimizeClearCommand)
#RUN php artisan optimize:clear
RUN php artisan route:clear && php artisan route:cache
RUN php artisan config:clear && php artisan config:cache
RUN php artisan event:clear && php artisan event:cache

# 优化mysql连接慢的问题
RUN sed -i "/->exec(\"use /d" "vendor/laravel/framework/src/Illuminate/Database/Connectors/MySqlConnector.php"

# 创建数据表
RUN rm -rf "${APP_DIR}/database/database.sqlite"
RUN touch "${APP_DIR}/database/database.sqlite"
RUN php artisan migrate --force

# 创建 preload.php
RUN php artisan opcache:create-preload --with_remote_scripts && php preload.php

#########################################################################################################
#### 运行环境
#########################################################################################################
FROM ${BASE_IMAGE}

COPY --from=builder --chown=www-data:www-data ${APP_DIR} ${APP_DIR}

RUN php /data/app/artisan opcache:compile-files --with_app_files --with_remote_cached_scripts
