<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-28 10:47:48 +0800
 */

namespace fwkit\Wechat\Message\Event;

class SubscribeMsgChange extends EventBase
{
    protected $list = [];

    protected function initialize(): void
    {
        $list = (array) $this->get('subscribeMsgChangeEvent.list');
        foreach ($list as $item) {
            $this->picList[] = [
                'templateId' => $item['templateid'] ?? null,
                'status'     => $item['subscribestatusstring'] ?? null,
            ];
        }
    }
}
