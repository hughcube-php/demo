<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/5/25
 * Time: 17:48
 */

namespace App\Enum;

/**
 * @method static null|int getDefault()
 */
class AAAEnum extends \HughCube\Enum\Enum
{
    /** 客户端类型 */
    protected const int ClientTypeEnum_UNKNOWN = 10001;
    protected const int ClientTypeEnum_POSTMEN = 10002;
    protected const int ClientTypeEnum_WECHAT_MP = 10003;

    /**
     * @param string $type
     * 格式化值, 如果type不存在返回null
     */
    protected static function normalize($type): null|int
    {
        return static::has($type) ? intval($type) : null;
    }
}
