<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Provider;

class Scope
{
    private static $scopes = [
        1   => '消息管理权限',
        2   => '用户管理权限',
        3   => '帐号服务权限',
        4   => '网页服务权限',
        5   => '微信小店权限',
        6   => '微信多客服权限',
        7   => '群发与通知权限',
        8   => '微信卡券权限',
        9   => '微信扫一扫权限',
        10  => '微信连WIFI权限',
        11  => '素材管理权限',
        12  => '微信摇周边权限',
        13  => '微信门店权限',
        14  => '微信支付权限',
        15  => '自定义菜单权限',
        22  => '城市服务接口权限',
        24  => '微信开放平台帐号绑定权限',
        26  => '微信电子发票权限',

        17  => '帐号管理权限',
        18  => '开发管理与数据分析权限',
        19  => '客服消息管理权限',
        30  => '小程序基本信息设置权限',
        37  => '小程序附近地点权限集',
        40  => '小程序插件管理权限集',
    ];

    public static function label($id)
    {
        return isset(self::$scopes[$id]) ? self::$scopes[$id] : null;
    }

    public static function labels(array $ids)
    {
        return array_filter(self::$scopes, function ($k) use ($ids) {
            return in_array($k, $ids);
        }, ARRAY_FILTER_USE_KEY);
    }
}
