<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 09:56:45 +0800
 */
namespace fwkit\Wechat\Concerns;

trait HasOptions
{
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    public function setOption(string $key, $value)
    {
        $method = 'set' . ucfirst($key);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } elseif (property_exists($this, $key)) {
            $this->{$key} = $value;
        }

        return $this;
    }
}
