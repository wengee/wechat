<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Traits;

trait HasOptions
{
    public function setOptions(array $options): void
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
