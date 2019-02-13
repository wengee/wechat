<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-01 18:38:17 +0800
 */
namespace Wechat;

use Illuminate\Support\Collection as IlluminateCollection;

class Collection extends IlluminateCollection
{
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            return $this->get($name);
        }
    }
}
