<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 11:30:57 +0800
 */
use fwkit\Wechat\ClientBase;
use fwkit\Wechat\Utils\Cache;
use fwkit\Wechat\Utils\CacheInterface;

if (!function_exists('wechat_set_cache')) {
    function wechat_set_cache(?CacheInterface $cache = null)
    {
        if ($cache) {
            Cache::setObj($cache);
        }
    }
}

if (!function_exists('wechat_guzzle_handler')) {
    function wechat_guzzle_handler($handler)
    {
        return ClientBase::setDefaultHandler($handler);
    }
}
