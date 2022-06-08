<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-08 16:33:51 +0800
 */

namespace fwkit\Wechat\Message\Event;

class KfMsgOrEvent extends EventBase
{
    public $token;

    protected function initialize(): void
    {
        $this->token = $this->get('token');
    }
}
