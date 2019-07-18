<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-19 06:20:34 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Raw extends ReplyBase
{
    protected $attributes = [
        'xml' => '',
    ];

    protected function template(): string
    {
        return $this->attributes['xml'] ?? '';
    }
}
