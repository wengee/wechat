<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 17:19:06 +0800
 */
use fwkit\Wechat\ClientBase;

if (!function_exists('wechat_cache_callback')) {
    function wechat_cache_callback(?callable $setCacheFunc = null, ?callable $getCacheFunc = null)
    {
        return ClientBase::cacheCallback($setCacheFunc, $getCacheFunc);
    }
}

if (!function_exists('wechat_guzzle_handler')) {
    function wechat_guzzle_handler($handler)
    {
        return ClientBase::setDefaultHandler($handler);
    }
}
