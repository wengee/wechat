<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-04 17:59:31 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Concerns\HasOptions;
use JsonSerializable;

class MiniprogramNotice implements JsonSerializable
{
    use HasOptions;

    public $appId;

    public $page;

    public $title;

    public $description;

    public $emphasisFirstItem = false;

    public $contentItem;

    public function __constrcut(array $options)
    {
        $this->setOptions($options);
    }

    public function jsonSerialize()
    {
        return [
            'appid'                 => $this->appId,
            'page'                  => $this->page,
            'title'                 => $this->title,
            'description'           => $this->description,
            'emphasis_first_item'   => (bool) $this->emphasisFirstItem,
            'content_item'          => $this->contentItem,
        ];
    }
}
