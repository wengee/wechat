<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:51:02 +0800
 */
namespace fwkit\Wechat\Message\Event;

class Subscribe extends EventBase
{
    public $ticket;

    public $scene;

    protected function initialize(array $data)
    {
        parent::initialize($data);
        $eventKey = $this->eventKey;
        if (strpos($eventKey, 'qrscene_')) {
            $this->scene = substr($eventKey, 8);
        }
    }
}
