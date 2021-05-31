<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-31 14:52:07 +0800
 */

namespace fwkit\Wechat\Message\Event;

class SubscribeMsgChange extends EventBase
{
    /** @var array */
    public $list = [];

    protected function initialize(): void
    {
        $list = (array) $this->get('subscribeMsgChangeEvent');
        foreach ($list as $item) {
            $data = $item['list'] ?? $item;

            if (empty($data) || empty($data['templateid'])) {
                continue;
            }

            $this->list[] = [
                'templateId' => $data['templateid'],
                'status'     => $data['subscribestatusstring'] ?? null,
            ];
        }
    }
}
