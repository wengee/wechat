<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:33 +0800
 */
namespace fwkit\Wechat\Message;

class ShortVideo extends MessageBase
{
    public $mediaId;

    public $thumbMediaId;

    protected function initialize()
    {
        $this->mediaId = $this->get('mediaId');
        $this->thumbMediaId = $this->get('thumbMediaId');
    }
}
