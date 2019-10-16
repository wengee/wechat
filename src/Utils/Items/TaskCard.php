<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-10-16 10:34:45 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Concerns\HasOptions;
use JsonSerializable;

class TastCard implements JsonSerializable
{
    use HasOptions;

    public $title;

    public $description;

    public $url;

    public $taskId;

    public $btn = [];

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

    public function setBtn($data)
    {
        if (is_array($data)) {
            if (Helper::isAssoc($data)) {
                $this->btn = [new TaskCardBtn($data)];
            } else {
                $this->btn = array_map(function ($item) {
                    if (is_array($item)) {
                        return new TaskCardBtn($item);
                    } elseif ($item instanceof TaskCardBtn) {
                        return $item;
                    } else {
                        return null;
                    }
                }, $data);
            }
        } elseif ($data instanceof TaskCardBtn) {
            $this->btn = [$data];
        }
    }
}
