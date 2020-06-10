<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Provider;

class Type
{
    private static $services = [
        0 => '订阅号',
        1 => '订阅号',
        2 => '服务号',
    ];

    private static $verifies = [
        -1 => '未认证',
        0  => '微信认证',
        1  => '新浪微博认证',
        2  => '腾讯微博认证',
        3  => '已资质认证通过但还未通过名称认证',
        4  => '已资质认证通过、还未通过名称认证，但通过了新浪微博认证',
        5  => '已资质认证通过、还未通过名称认证，但通过了腾讯微博认证',
    ];

    public static function serviceLabel($id)
    {
        return isset(self::$services[$id]) ? self::$services[$id] : null;
    }

    public static function verifyLabel($id)
    {
        return isset(self::$verifies[$id]) ? self::$verifies[$id] : null;
    }
}
