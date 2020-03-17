<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-03-17 14:40:08 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Traits\HasOptions;
use JsonSerializable;

class TaskCard implements JsonSerializable
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
