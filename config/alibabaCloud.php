<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2021/2/23
 * Time: 15:02
 */

return [
    "default" => "cn-hangzhou",

    "clients" => [
        'cn-hangzhou' => [
            "AccessKeyID" => env("ALIYUN_ACCESS_KEY_ID"),
            "AccessKeySecret" => env("ALIYUN_ACCESS_KEY_SECRET"),
            "RegionId" => 'cn-hangzhou',
            "AccountId" => env("ALIYUN_ACCOUNT_ID")
        ],
    ]
];
