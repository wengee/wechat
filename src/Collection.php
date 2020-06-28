<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-17 15:03:38 +0800
 */

namespace fwkit\Wechat;

use Illuminate\Support\Collection as IlluminateCollection;

class Collection extends IlluminateCollection
{
    public function __get($name)
    {
        try {
            $value = parent::__get($name);
        } catch (\Exception $e) {
            $value = $this->get($name);
        }

        if (is_array($value)) {
            $value = new static($value);
        }

        return $value;
    }

    public function offsetGet($key)
    {
        return $this->items[$key] ?? null;
    }
}
