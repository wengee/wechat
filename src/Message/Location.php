<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-05-08 15:17:37 +0800
 */
namespace fwkit\Wechat\Message;

class Video extends MessageBase
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
