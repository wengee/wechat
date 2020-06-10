<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class Image extends MessageBase
{
    public $picUrl;

    public $mediaId;

    protected function initialize(): void
    {
        $this->picUrl = $this->get('picUrl');
        $this->mediaId = $this->get('mediaId');
    }
}
