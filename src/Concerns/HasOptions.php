<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-01 15:59:44 +0800
 */
namespace Wechat\Concerns;

trait HasOptions
{
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
