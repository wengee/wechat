<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 10:55:00 +0800
 */
namespace fwkit\Wechat\Concerns;

use fwkit\Wechat\CacheInterface;

trait HasCache
{
    protected static $cache;

    public function cacheSet(string $key, $value, int $ttl = 0)
    {
        if (self::$cache) {
            $key = 'wechat:' . $this->appId . ':' . $key;
            return self::$cache->set($key, $value, $ttl);
        }
    }

    public function cacheGet(string $key)
    {
        if (self::$cache) {
            $key = 'wechat:' . $this->appId . ':' . $key;
            return self::$cache->get($key);
        }

        return null;
    }

    public static function setCacheObj(CacheInterface $cache)
    {
        self::$cache = $cache;
    }
}
