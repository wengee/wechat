<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-18 18:19:22 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Raw extends ReplyBase
{
    protected $attributes = [
        'xml' => '',
    ];

    public function toXml(): string
    {
        return $this->attributes['xml'] ?? '';
    }
}
