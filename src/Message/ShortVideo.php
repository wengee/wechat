<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class ShortVideo extends MessageBase
{
    public $mediaId;

    public $thumbMediaId;

    protected function initialize(): void
    {
        $this->mediaId = $this->get('mediaId');
        $this->thumbMediaId = $this->get('thumbMediaId');
    }
}
