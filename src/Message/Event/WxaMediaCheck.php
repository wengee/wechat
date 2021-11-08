<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-08 17:24:31 +0800
 */

namespace fwkit\Wechat\Message\Event;

class WxaMediaCheck extends EventBase
{
    public $traceId;

    public $appId;

    public $version;

    public $result;

    public $detail;

    protected function initialize(): void
    {
        $this->traceId  = $this->get('trace_id');
        $this->appId    = $this->get('appId');
        $this->version  = $this->get('version');
        $this->result   = $this->get('result');
        $this->detail   = $this->get('detail');
    }
}
