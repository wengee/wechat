<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 15:36:09 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Unknown extends ReplyBase
{
    protected function template(): string
    {
        return '';
    }
}
