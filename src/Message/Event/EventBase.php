<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:32:49 +0800
 */
namespace fwkit\Wechat\Message\Event;

use fwkit\Wechat\Message\MessageBase;

abstract class EventBase extends MessageBase
{
    public $event;

    public $eventKey;

    protected function setData(array $data, array $map = [])
    {
        $this->event = isset($data['event']) ? strtolower($data['event']) : null;
        $this->eventKey = isset($data['eventkey']) ? strtolower($data['eventkey']) : null;
        parent::setData($data, $map);
    }
}
