<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-03 14:32:31 +0800
 */
namespace fwkit\Wechat\Message\Event;

use fwkit\Wechat\Message\MessageBase;

abstract class EventBase extends MessageBase
{
    public $event;

    public $eventKey;

    public function isEvent(...$types)
    {
        if (count($types) === 0) {
            return true;
        }

        return in_array($this->event, $types, true);
    }

    protected function setData(array $data, array $map = [])
    {
        $this->event = isset($data['event']) ? strtolower($data['event']) : null;
        $this->eventKey = isset($data['eventkey']) ? $data['eventkey'] : null;
        parent::setData($data, $map);
    }
}
