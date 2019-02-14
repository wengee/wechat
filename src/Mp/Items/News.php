<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 15:16:37 +0800
 */
namespace fwkit\Wechat\Mp\Items;

use fwkit\Wechat\Concerns\HasOptions;
use JsonSerializable;

class News implements JsonSerializable
{
    use HasOptions;

    public $title;

    public $description;

    public $url;

    public $picUrl;

    public function __constrcut(array $options)
    {
        $this->setOptions($options);
    }

    public function jsonSerialize()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'picurl' => $this->picUrl,
        ];
    }
}
