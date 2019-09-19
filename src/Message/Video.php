<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-19 10:09:59 +0800
 */
namespace fwkit\Wechat\Message;

class Video extends MessageBase
{
    protected $properties = [
        'mediaId',
        'thumbMediaId',
    ];

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
