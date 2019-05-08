<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:19:22 +0800
 */
namespace fwkit\Wechat\Message;

class ShortVideo extends MessageBase
{
    public $mediaId;

    public $thumbMediaId;

    protected function initialize(array $data)
    {
        $this->setData($data, [
            'mediaid' => 'mediaId',
            'thumbmediaid' => 'thumbMediaId',
        ]);
    }
}
