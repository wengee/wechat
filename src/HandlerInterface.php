<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-13 14:59:17 +0800
 */

namespace fwkit\Wechat;

interface HandlerInterface
{
    public function __invoke(ClientBase $wechat, $message, callable $next);
}
