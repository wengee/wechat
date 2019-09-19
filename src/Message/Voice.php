<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-19 10:10:15 +0800
 */
namespace fwkit\Wechat\Message;

class Voice extends MessageBase
{
    protected $properties = [
        'format',
        'mediaId',
        'recognition',
    ];

    public $format;

    public $mediaId;

    public $recognition;

    protected function initialize(array $data)
    {
        $this->setData($data, [
            'mediaid' => 'mediaId',
        ]);
    }
}
