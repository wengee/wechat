<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-10-16 09:57:42 +0800
 */
namespace fwkit\Wechat\Message\Event;

class Subscribe extends EventBase
{
    public $ticket;

    public $scene;

    protected function initialize()
    {
        $this->ticket = $this->get('ticket');
        $eventKey = $this->eventKey;
        if (strpos($eventKey, 'qrscene_') === 0) {
            $this->scene = substr($eventKey, 8);
        }
    }
}
