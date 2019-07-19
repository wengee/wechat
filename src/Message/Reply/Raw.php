<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-19 15:30:40 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Raw extends ReplyBase
{
    protected $directOutput = true;

    protected $attributes = [
        'xml' => '',
    ];

    protected function template(): string
    {
        return $this->attributes['xml'] ?? '';
    }
}
