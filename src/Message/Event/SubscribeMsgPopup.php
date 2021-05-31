<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-31 14:51:03 +0800
 */

namespace fwkit\Wechat\Message\Event;

class SubscribeMsgPopup extends EventBase
{
    /** @var array */
    public $list = [];

    protected function initialize(): void
    {
        $list = (array) $this->get('subscribeMsgPopupEvent');
        foreach ($list as $item) {
            $data = $item['list'] ?? $item;

            if (empty($data) || empty($data['templateid'])) {
                continue;
            }

            $this->list[] = [
                'templateId' => $data['templateid'],
                'status'     => $data['subscribestatusstring'] ?? null,
                'scene'      => $data['popupscene'] ?? null,
            ];
        }
    }
}
