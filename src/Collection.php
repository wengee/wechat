<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-10-27 19:39:01 +0800
 */
namespace fwkit\Wechat;

use ArrayObject;

class Collection extends ArrayObject
{
    public function __construct(array $input = [])
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);
    }

    public function get(string $name, $default = null)
    {
        return $this[$name] ?? $default;
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
