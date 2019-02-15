<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 15:37:55 +0800
 */
namespace fwkit\Wechat\Message\Reply;

interface ReplyInterface
{
    public function toXml(): string;

    public function __toString(): string;
}
