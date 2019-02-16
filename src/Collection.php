<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:09:21 +0800
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
}
