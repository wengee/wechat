<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-29 18:24:35 +0800
 */

use fwkit\Wechat\Utils\Cache;
use fwkit\Wechat\Utils\CacheInterface;

if (!function_exists('wechat_set_cache')) {
    function wechat_set_cache(?CacheInterface $cache = null): void
    {
        if ($cache) {
            Cache::setObj($cache);
        }
    }
}
