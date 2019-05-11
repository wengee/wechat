<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-05-11 18:07:17 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Raw extends ReplyBase
{
    public $xml;

    protected function template(): string
    {
        return $this->xml;
    }
}
