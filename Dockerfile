
ARG BASE_IMAGE=crpi-vrt7o5qsysdzjsbh.cn-shanghai.personal.cr.aliyuncs.com/zycube/demo:base-latest

# 代码构建
FROM ${BASE_IMAGE}-composer AS builder

# composer 慧哲私有仓库的账号密码
ARG COMPOSER_HZCUBE_USERNAME
ARG COMPOSER_HZCUBE_PASSWORD

COPY . .

# 清除框架缓存
RUN rm -rf /app/bootstrap/cache/*
RUN find /app -name '.git' | xargs rm -rf

# 使用慧哲镜像
#RUN composer config --global repositories.hzcube composer https://packagist.x4k.net/composer
#RUN composer config --global --auth http-basic.packagist.x4k.net ${COMPOSER_HZCUBE_USERNAME} ${COMPOSER_HZCUBE_PASSWORD}

# composer安装依赖
RUN composer install --prefer-dist --optimize-autoloader --no-dev --profile
RUN composer dump-autoload --optimize --classmap-authoritative

# 优化mysql连接慢的问题
RUN sed -i "/->exec(\"use /d" "vendor/laravel/framework/src/Illuminate/Database/Connectors/MySqlConnector.php"
RUN /usr/local/bin/php -l "vendor/laravel/framework/src/Illuminate/Database/Connectors/MySqlConnector.php"

# 禁止每次worker启动清除opCache
RUN sed -i "/->clearOpcodeCache()/d" "vendor/laravel/octane/src/Swoole/Handlers/OnWorkerStart.php"
RUN /usr/local/bin/php -l "vendor/laravel/octane/src/Swoole/Handlers/OnWorkerStart.php"

# 不需要处理静态文件
RUN sed -i "/public function canServeRequestAsStaticFile(Request \$request, RequestContext \$context): bool/ {n; s/{/{\n        return false;/}" "vendor/laravel/octane/src/Swoole/SwooleClient.php"
RUN /usr/local/bin/php -l vendor/laravel/octane/src/Swoole/SwooleClient.php

#RUN sed -i 's/if\s*(\s*\$this->app->runningUnitTests()\s*)/if (null === \$driver \&\& $this->app->runningUnitTests())/' vendor/laravel/framework/src/Illuminate/Log/LogManager.php
#RUN php -l vendor/laravel/framework/src/Illuminate/Log/LogManager.php

# 更加深入的性能采集
#RUN sed -i "111a\        xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);" vendor/laravel/octane/bin/swoole-server && php -l vendor/laravel/octane/bin/swoole-server
#RUN sed -i "111a\        return \$this;" vendor/hughcube/profiler/src/Profiler.php && php -l vendor/hughcube/profiler/src/Profiler.php
#RUN sed -i "54c\        'enable.probability' => 1000000," config/profiler.php && php -l config/profiler.php

# 服务初始化就引入autoload.php
#RUN sed -i "/return include __DIR__ .*swoole-server';/i require_once __DIR__ . '\/\.\.\/autoload.php';" "vendor/bin/swoole-server"
#RUN php -l "vendor/bin/swoole-server"

# (一定要在修改代码之后)优化框架(\Illuminate\Foundation\Console\OptimizeClearCommand)
RUN /usr/local/bin/php artisan view:clear && php artisan view:cache
RUN /usr/local/bin/php artisan event:clear && php artisan event:cache
RUN /usr/local/bin/php artisan route:clear && php artisan route:cache
RUN /usr/local/bin/php artisan config:clear && php artisan config:cache

# 创建 preload.php
RUN echo "<?php " > preload.php
RUN /usr/local/bin/php artisan opcache:create-preload #--with_remote_scripts
RUN /usr/local/bin/php preload.php

# 创建数据表
RUN rm -rf "${APP_BASE_PATH}/database/database.sqlite"
RUN touch "${APP_BASE_PATH}/database/database.sqlite"
RUN /usr/local/bin/php artisan migrate --force

# octane预处理
RUN /usr/local/bin/php artisan octane:prepare \
        --host="0.0.0.0" \
        --port=80 \
        --workers="${OCTANE_MAX_WORKERS:-2}" \
        --task-workers="${OCTANE_MAX_TASK_WORKERS:-1}" \
        --max-requests="${OCTANE_MAX_REQUESTS:-1000}" \
        --state-file="${OCTANE_STATE_FILE}"

#########################################################################################################
#### 运行环境
#########################################################################################################
FROM ${BASE_IMAGE}

COPY --from=builder --chown=www-data:www-data ${APP_BASE_PATH} ${APP_BASE_PATH}

RUN /usr/local/bin/php /data/app/artisan opcache:compile-files --with_app_files #--with_remote_cached_scripts
