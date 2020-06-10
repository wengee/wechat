<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
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
