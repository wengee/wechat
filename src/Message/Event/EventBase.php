<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

use fwkit\Wechat\Message\MessageBase;

abstract class EventBase extends MessageBase
{
    public $event;

    public $eventKey;

    public function isEvent(...$types): bool
    {
        if (count($types) === 0) {
            return true;
        }

        return in_array($this->event, $types, true);
    }

    protected function setData(array $data): void
    {
        parent::setData($data);
        $this->event = isset($data['event']) ? strtolower($data['event']) : null;
        $this->eventKey = isset($data['eventkey']) ? strval($data['eventkey']) : null;
    }
}
