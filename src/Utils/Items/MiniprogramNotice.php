<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Traits\HasOptions;
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

    public function __construct(array $options)
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
