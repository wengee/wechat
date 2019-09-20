<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:16:43 +0800
 */
namespace fwkit\Wechat\Message\Event;

class Scan extends EventBase
{
    public $ticket;

    public $scene;

    protected function initialize()
    {
        $this->ticket = $this->get('ticket');
        $this->scene = $this->get('eventKey');
    }
}
