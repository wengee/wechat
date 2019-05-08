<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-05-08 15:14:29 +0800
 */
namespace fwkit\Wechat;

use fwkit\Wechat\Message\MessageBase;

interface HandlerInterface
{
    public function __invoke(ClientBase $wechat, MessageBase $message);
}
