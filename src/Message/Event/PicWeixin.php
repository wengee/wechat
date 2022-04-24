<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-04-24 14:19:20 +0800
 */

namespace fwkit\Wechat\Message\Event;

use fwkit\Wechat\Utils\Helper;

class PicWeixin extends EventBase
{
    public $count = 0;

    public $picList = [];

    protected function initialize(): void
    {
        $this->count = (int) $this->get('sendPicsInfo.count');
        $picList     = (array) $this->get('sendPicsInfo.picList');
        foreach ($picList as $item) {
            $this->picList[] = [
                'md5sum' => Helper::arrGet($item, 'item.picmd5sum'),
            ];
        }
    }
}
