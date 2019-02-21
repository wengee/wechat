<?php

namespace fwkit\Wechat;

interface CacheInterface
{
    public function set(string $key, $value, int $ttl = 0);

    public function get(string $key);
}