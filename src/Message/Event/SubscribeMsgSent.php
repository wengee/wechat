<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-31 14:16:59 +0800
 */

namespace fwkit\Wechat\Message\Event;

class SubscribeMsgSent extends EventBase
{
    /** @var array */
    protected $list = [];

    protected function initialize(): void
    {
        $list = (array) $this->get('subscribeMsgSentEvent.list');
        foreach ($list as $item) {
            $this->list[] = [
                'templateId'  => $item['templateid'] ?? null,
                'msgId'       => $item['msgid'] ?? null,
                'errorCode'   => $item['errorcode'] ?? 0,
                'errorStatus' => $item['errorstatus'] ?? null,
            ];
        }
    }
}
