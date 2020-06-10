<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class Voice extends MessageBase
{
    public $format;

    public $mediaId;

    public $recognition;

    protected function initialize(): void
    {
        $this->format = $this->get('format');
        $this->mediaId = $this->get('mediaId');
        $this->recognition = $this->get('recognition');
    }
}
