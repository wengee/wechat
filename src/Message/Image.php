<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-19 10:07:40 +0800
 */
namespace fwkit\Wechat\Message;

class Image extends MessageBase
{
    protected $properties = [
        'picUrl',
        'mediaId',
    ];

    public $picUrl;

    public $mediaId;

    protected function initialize(array $data)
    {
        $this->setData($data, [
            'picurl' => 'picUrl',
            'mediaid' => 'mediaId',
        ]);
    }
}
