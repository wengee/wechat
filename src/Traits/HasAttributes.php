<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-13 16:03:38 +0800
 */

namespace fwkit\Wechat\Traits;

trait HasAttributes
{
    protected $attributes = [];

    public function __get(string $property)
    {
        $method = 'get'.ucfirst($property);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->attributes[$property] ?? null;
    }

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function withoutAttribute($name)
    {
        unset($this->attributes[$name]);

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }
}
