<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:17 +0800
 */
namespace fwkit\Wechat\Message;

class Image extends MessageBase
{
    public $picUrl;

    public $mediaId;

    protected function initialize()
    {
        $this->picUrl = $this->get('picUrl');
        $this->mediaId = $this->get('mediaId');
    }
}
