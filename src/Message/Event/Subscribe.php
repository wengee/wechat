<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

class Subscribe extends EventBase
{
    public $ticket;

    public $scene;

    protected function initialize(): void
    {
        $this->ticket = $this->get('ticket');
        $eventKey = $this->eventKey;
        if (strpos($eventKey, 'qrscene_') === 0) {
            $this->scene = substr($eventKey, 8);
        }
    }
}
