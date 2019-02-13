<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-13 17:58:29 +0800
 */
namespace fwkit\Wechat\Concerns;

trait HasCache
{
    protected static $setCacheFunc;

    protected static $getCacheFunc;

    protected function cacheSet(string $key, $value, int $ttl = 0)
    {
        if (is_callable(self::$setCacheFunc)) {
            return call_user_func_array(self::$setCacheFunc, [$key, $value, $ttl]);
        }
    }

    protected function cacheGet(string $key)
    {
        if (is_callable(self::$getCacheFunc)) {
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
