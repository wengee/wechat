<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:17:00 +0800
 */
namespace fwkit\Wechat\Message\Event;

class ScanCodeWaitMsg extends EventBase
{
    public $codeType;

    public $codeResult;

    protected function initialize()
    {
        $this->codeType = $this->get('scanCodeInfo.scanType');
        $this->codeResult = $this->get('scanCodeInfo.scanResult');
    }
}
