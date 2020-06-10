<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

class ScanCodePush extends EventBase
{
    public $codeType;

    public $codeResult;

    protected function initialize(): void
    {
        $this->codeType = $this->get('scanCodeInfo.scanType');
        $this->codeResult = $this->get('scanCodeInfo.scanResult');
    }
}
