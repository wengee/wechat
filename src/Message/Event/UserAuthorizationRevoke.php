<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-08 10:43:49 +0800
 */

namespace fwkit\Wechat\Message\Event;

class UserAuthorizationRevoke extends EventBase
{
    public $relOpenId;

    public $appId;

    /** @var int */
    public $revokeInfo = 0;

    protected function initialize(): void
    {
        $this->relOpenId  = $this->get('openId');
        $this->appId      = $this->get('appId');
        $this->revokeInfo = (int) $this->get('revokeInfo');
    }
}
