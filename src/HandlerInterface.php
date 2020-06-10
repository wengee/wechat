<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat;

use fwkit\Wechat\Message\MessageBase;

interface HandlerInterface
{
    public function __invoke(ClientBase $wechat, MessageBase $message, callable $next);
}
