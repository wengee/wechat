<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:47 +0800
 */
namespace fwkit\Wechat\Message;

class Voice extends MessageBase
{
    public $format;

    public $mediaId;

    public $recognition;

    protected function initialize()
    {
        $this->format = $this->get('format');
        $this->mediaId = $this->get('mediaId');
        $this->recognition = $this->get('recognition');
    }
}
