<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-04 18:04:03 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Concerns\HasOptions;
use JsonSerializable;

class TastCardBtn implements JsonSerializable
{
    use HasOptions;

    public $key;

    public $name;

    public $replaceName;

    public $color = 'blue';

    public $bold = false;

    public function __constrcut(array $options)
    {
        $this->setOptions($options);
    }

    public function jsonSerialize()
    {
        return [
            'key'           => $this->key,
            'name'          => $this->name,
            'replace_name'  => $this->replaceName,
            'color'         => $this->color,
            'is_bold'       => $this->bold,
        ];
    }
}
