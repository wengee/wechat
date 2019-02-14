<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 16:35:37 +0800
 */
namespace Wechat;

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
}
