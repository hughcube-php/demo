<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/8/31
 * Time: 18:12
 */

use App\Http\DevOps\Controllers\HealthcheckController as DevOpsHealthcheckController;
use App\Http\DevOps\Middleware\OnlyLocalGuard;
use HughCube\Laravel\Knight\OPcache\Actions\ResetAction as OPcacheResetAction;

/** php opcache */
Route::any('/healthcheck', DevOpsHealthcheckController::class);


/** php opcache */
Route::any('/opcache/reset', OPcacheResetAction::class)->middleware(OnlyLocalGuard::class);
