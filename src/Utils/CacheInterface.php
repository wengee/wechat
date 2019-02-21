<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 11:30:23 +0800
 */
namespace fwkit\Wechat\Utils;

interface CacheInterface
{
    public function set(string $key, $value, int $ttl = 0);

    public function get(string $key);
}
