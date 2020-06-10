<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
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

if (!function_exists('array_change_key_case_recursive')) {
    function array_change_key_case_recursive(array $data, int $case = CASE_LOWER)
    {
        return array_map(function ($item) use ($case) {
            if (is_array($item)) {
                $item = array_change_key_case_recursive($item, $case);
            }

            return $item;
        }, array_change_key_case($data, $case));
    }
}
