<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-28 10:49:33 +0800
 */

namespace fwkit\Wechat\Message\Event;

class SubscribeMsgSent extends EventBase
{
    protected $list = [];

    protected function initialize(): void
    {
        $list = (array) $this->get('subscribeMsgSentEvent.list');
        foreach ($list as $item) {
            $this->picList[] = [
                'templateId'  => $item['templateid'] ?? null,
                'msgId'       => $item['msgid'] ?? null,
                'errorCode'   => $item['errorcode'] ?? 0,
                'errorStatus' => $item['errorstatus'] ?? null,
            ];
        }
    }
}
