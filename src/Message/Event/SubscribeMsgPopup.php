<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-31 14:17:04 +0800
 */

namespace fwkit\Wechat\Message\Event;

class SubscribeMsgPopup extends EventBase
{
    /** @var array */
    protected $list = [];

    protected function initialize(): void
    {
        $list = (array) $this->get('subscribeMsgPopupEvent.list');
        foreach ($list as $item) {
            $this->list[] = [
                'templateId' => $item['templateid'] ?? null,
                'status'     => $item['subscribestatusstring'] ?? null,
                'scene'      => $item['popupscene'] ?? null,
            ];
        }
    }
}
