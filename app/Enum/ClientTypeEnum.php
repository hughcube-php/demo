<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/5/24
 * Time: 18:52
 */

namespace App\Enum;

use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method static boolean isUnknown(int $type)
 * @method static boolean isPostmen(int $type)
 * @method static boolean isWechatMp(int $type)
 */
class ClientTypeEnum extends AAAEnum
{
    const int UNKNOWN = self::ClientTypeEnum_UNKNOWN;
    const int POSTMEN = self::ClientTypeEnum_POSTMEN;
    const int WECHAT_MP = self::ClientTypeEnum_WECHAT_MP;

    /**
     * @return array<int|string, array<string, string>>
     */
    public static function labels(): array
    {
        return [
            static::UNKNOWN => ['title' => '未知', 'name' => 'unknown'],
            static::POSTMEN => ['title' => 'postmen', 'name' => 'postmen'],
            static::WECHAT_MP => ['title' => '小程序', 'name' => 'wechatMp'],
        ];
    }
}
