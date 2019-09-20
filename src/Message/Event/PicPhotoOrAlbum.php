<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:21:44 +0800
 */
namespace fwkit\Wechat\Message\Event;

class PicPhotoOrAlbum extends EventBase
{
    public $count = 0;

    public $picList = [];

    protected function initialize()
    {
        $this->count = (int) $this->get('sendPicsInfo.count');
        $picList = (array) $this->get('sendPicsInfo.picList');
        foreach ($picList as $item) {
            $this->picList[] = [
                'md5sum' => array_get($item, 'item.picmd5sum'),
            ];
        }
    }
}
