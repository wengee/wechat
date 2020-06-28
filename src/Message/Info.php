<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-28 17:38:53 +0800
 */

namespace fwkit\Wechat\Message;

class Info extends MessageBase
{
    public $appId;

    public $infoType;

    public $componentVerifyTicket;

    public $authorizerAppId;

    public $authorizationCode;

    public $authorizationCodeExpiredTime;

    public $preAuthCode;

    protected function initialize(): void
    {
        $this->type = 'info';
        $this->appId = $this->get('appId');
        $this->infoType = $this->get('infoType');
        $this->componentVerifyTicket = $this->get('componentVerifyTicket');
        $this->authorizerAppId = $this->get('authorizerAppId');
        $this->authorizationCode = $this->get('authorizationCode');
        $this->authorizationCodeExpiredTime = (int) $this->get('authorizationCodeExpiredTime');
        $this->preAuthCode = $this->get('preAuthCode');
    }
}
