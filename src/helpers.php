<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 10:53:23 +0800
 */
use fwkit\Wechat\CacheInterface;
use fwkit\Wechat\ClientBase;

if (!function_exists('wechat_set_cache')) {
    function wechat_set_cache(?CacheInterface $cache = null)
    {
        if ($cache) {
            ClientBase::setCacheObj($cache);
        }
    }
}

if (!function_exists('wechat_guzzle_handler')) {
    function wechat_guzzle_handler($handler)
    {
        return ClientBase::setDefaultHandler($handler);
    }
}
