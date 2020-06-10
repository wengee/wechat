<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Traits\HasOptions;
use JsonSerializable;

class TextCard implements JsonSerializable
{
    use HasOptions;

    public $title;

    public $description;

    public $url;

    public $btnTxt;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    public function jsonSerialize()
    {
        return [
            'title'         => $this->title,
            'description'   => $this->description,
            'url'           => $this->url,
            'btntxt'        => $this->btnTxt,
        ];
    }
}
