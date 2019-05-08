<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:19:40 +0800
 */
namespace fwkit\Wechat\Message;

class Voice extends MessageBase
{
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
