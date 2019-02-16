<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:05:18 +0800
 */
namespace fwkit\Wechat\Concerns;

trait HasCache
{
    protected static $setCacheFunc;

    protected static $getCacheFunc;

    public function cacheSet(string $key, $value, int $ttl = 0)
    {
        if (is_callable(self::$setCacheFunc)) {
            $key = 'wechat:' . $this->appId . ':' . $key;
            return call_user_func_array(self::$setCacheFunc, [$key, $value, $ttl]);
        }
    }

    public function cacheGet(string $key)
    {
        if (is_callable(self::$getCacheFunc)) {
            $key = 'wechat:' . $this->appId . ':' . $key;
            return call_user_func_array(self::$getCacheFunc, [$key]);
        }

        return null;
    }

    public static function cacheCallback(?callable $setCacheFunc = null, ?callable $getCacheFunc = null)
    {
        self::$setCacheFunc = $setCacheFunc;
        self::$getCacheFunc = $getCacheFunc;
    }
}
