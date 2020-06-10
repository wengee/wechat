<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils;

class Cache
{
    protected static $cache;

    public static function set(string $appId, string $key, $value, int $ttl = 0)
    {
        if (self::$cache) {
            $key = 'wechat:' . $appId . ':' . $key;
            return self::$cache->set($key, $value, $ttl);
        }
    }

    public static function get(string $appId, string $key)
    {
        if (self::$cache) {
            $key = 'wechat:' . $appId . ':' . $key;
            return self::$cache->get($key);
        }

        return null;
    }

    public static function setObj(CacheInterface $cache): void
    {
        self::$cache = $cache;
    }
}
