<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-04 17:58:16 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Concerns\HasOptions;
use JsonSerializable;

class TextCard implements JsonSerializable
{
    use HasOptions;

    public $title;

    public $description;

    public $url;

    public $btnTxt;

    public function __constrcut(array $options)
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
