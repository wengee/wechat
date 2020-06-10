<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-10 15:12:44 +0800
 */

namespace fwkit\Wechat\Message\Event;

use Illuminate\Support\Arr;

class PicPhotoOrAlbum extends EventBase
{
    public $count = 0;

    public $picList = [];

    protected function initialize(): void
    {
        $this->count = (int) $this->get('sendPicsInfo.count');
        $picList = (array) $this->get('sendPicsInfo.picList');
        foreach ($picList as $item) {
            $this->picList[] = [
                'md5sum' => Arr::get($item, 'item.picmd5sum'),
            ];
        }
    }
}
