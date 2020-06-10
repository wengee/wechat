<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

class Scan extends EventBase
{
    public $ticket;

    public $scene;

    protected function initialize(): void
    {
        $this->ticket = $this->get('ticket');
        $this->scene = $this->get('eventKey');
    }
}
